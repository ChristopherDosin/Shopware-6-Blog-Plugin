<?php declare(strict_types=1);
namespace Sas\BlogModule\Content\Blog\DataResolver;

use Sas\BlogModule\Content\Blog\BlogEntriesDefinition;
use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\DataResolver\CriteriaCollection;
use Shopware\Core\Content\Cms\DataResolver\Element\AbstractCmsElementResolver;
use Shopware\Core\Content\Cms\DataResolver\Element\ElementDataCollection;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\ResolverContext;
use Shopware\Core\Content\Product\SalesChannel\Listing\Filter;
use Shopware\Core\Content\Product\SalesChannel\Listing\FilterCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Aggregation\Bucket\FilterAggregation;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Aggregation\Metric\EntityAggregation;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\RangeFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;

class BlogCmsElementResolver extends AbstractCmsElementResolver
{
    public function getType(): string
    {
        return 'blog';
    }

    public function collect(CmsSlotEntity $slot, ResolverContext $resolverContext): ?CriteriaCollection
    {
        /* get the config from the element */
        $config = $slot->getFieldConfig();

        $dateTime = (new \DateTime());

        $criteria = new Criteria();

        $criteria->addFilter(
            new EqualsFilter('active', true),
            new RangeFilter('publishedAt', [RangeFilter::LTE => $dateTime->format(DATE_ATOM)])
        );

        $criteria->addAssociations([
            'author',
            'author.media',
            'author.blogs',
            'blogCategories'
        ]);

        $criteria->addSorting(
            new FieldSorting('publishedAt', FieldSorting::DESCENDING)
        );

        if ($config->has('showType') && $config->get('showType')->getValue() === 'select') {
            $blogCategories = $config->get('blogCategories') ? $config->get('blogCategories')->getValue() : [];

            $criteria->addFilter(new EqualsAnyFilter('blogCategories.id', $blogCategories));
        }

        $request = $resolverContext->getRequest();
        $limit = 1;

        if ($config->has('paginationCount') && $config->get('paginationCount')->getValue()) {
            $limit = (int) $config->get('paginationCount')->getValue();
        }

        $context = $resolverContext->getSalesChannelContext();

        $this->handlePagination($limit, $request, $criteria, $context);
        $this->handleFilters($request, $criteria, $context);

        $criteriaCollection = new CriteriaCollection();

        $criteriaCollection->add(
            'sas_blog',
            BlogEntriesDefinition::class,
            $criteria
        );

        return $criteriaCollection;
    }

    public function enrich(CmsSlotEntity $slot, ResolverContext $resolverContext, ElementDataCollection $result): void
    {
        $slot->setData($result->get('sas_blog'));
    }

    private function handlePagination(int $limit, Request $request, Criteria $criteria, SalesChannelContext $context): void
    {
        $page = $this->getPage($request);

        $criteria->setOffset(($page - 1) * $limit);
        $criteria->setLimit($limit);
        $criteria->setTotalCountMode(Criteria::TOTAL_COUNT_MODE_EXACT);
    }

    private function getPage(Request $request): int
    {
        $page = $request->query->getInt('p', 1);

        if ($request->isMethod(Request::METHOD_POST)) {
            $page = $request->request->getInt('p', $page);
        }

        return $page <= 0 ? 1 : $page;
    }

    private function handleFilters(Request $request, Criteria $criteria, SalesChannelContext $context): void
    {
        $criteria->addAssociation('blogCategories');
        $criteria->addAssociation('authors');

        $filters = $this->getFilters($request, $context);

        $aggregations = $this->getAggregations($request, $filters);

        foreach ($aggregations as $aggregation) {
            $criteria->addAggregation($aggregation);
        }

        foreach ($filters as $filter) {
            if ($filter->isFiltered()) {
                $criteria->addPostFilter($filter->getFilter());
            }
        }

        $criteria->addExtension('filters', $filters);
    }

    private function getAggregations(Request $request, FilterCollection $filters): array
    {
        $aggregations = [];

        if ($request->get('reduce-aggregations', null) === null) {
            foreach ($filters as $filter) {
                $aggregations = array_merge($aggregations, $filter->getAggregations());
            }

            return $aggregations;
        }

        foreach ($filters as $filter) {
            $excluded = $filters->filtered();

            if ($filter->exclude()) {
                $excluded = $excluded->blacklist($filter->getName());
            }

            foreach ($filter->getAggregations() as $aggregation) {
                if ($aggregation instanceof FilterAggregation) {
                    $aggregation->addFilters($excluded->getFilters());

                    $aggregations[] = $aggregation;

                    continue;
                }

                $aggregation = new FilterAggregation(
                    $aggregation->getName() . '-filtered',
                    $aggregation,
                    $excluded->getFilters()
                );

                $aggregations[] = $aggregation;
            }
        }

        return $aggregations;
    }

    private function getFilters(Request $request, SalesChannelContext $context): FilterCollection
    {
        $filters = new FilterCollection();

        $filters->add($this->getCategoriesFilter($request));
        $filters->add($this->getAuthorsFilter($request));

        return $filters;
    }

    private function getCategoriesFilter(Request $request): Filter
    {
        $ids = $this->getFilterByCustomIds('categories', $request);

        return new Filter(
            'categories',
            !empty($ids),
            [
                new EntityAggregation('blogCategories', 'blogCategories.id', 'sas_blog_category'),
            ],
            new EqualsAnyFilter('blogCategories.id', $ids),
            $ids
        );
    }

    private function getAuthorsFilter(Request $request): Filter
    {
        $ids = $this->getFilterByCustomIds('authors', $request);

        return new Filter(
            'authors',
            !empty($ids),
            [
                new EntityAggregation('authors', 'authorId', 'sas_blog_author'),
            ],
            new EqualsAnyFilter('authorId', $ids),
            $ids
        );
    }

    private function getFilterByCustomIds(string $input, Request $request): array
    {
        $ids = $request->query->get($input, '');

        if ($request->isMethod(Request::METHOD_POST)) {
            $ids = $request->request->get($input, '');
        }

        $ids = explode('|', $ids);

        return array_filter($ids);
    }
}
