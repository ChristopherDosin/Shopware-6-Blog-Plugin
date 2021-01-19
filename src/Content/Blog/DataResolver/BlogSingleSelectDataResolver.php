<?php declare(strict_types=1);
namespace Sas\BlogModule\Content\Blog\DataResolver;

use Sas\BlogModule\Content\Blog\BlogEntriesDefinition;
use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\DataResolver\CriteriaCollection;
use Shopware\Core\Content\Cms\DataResolver\Element\AbstractCmsElementResolver;
use Shopware\Core\Content\Cms\DataResolver\Element\ElementDataCollection;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\ResolverContext;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

class BlogSingleSelectDataResolver extends AbstractCmsElementResolver
{
    public function getType(): string
    {
        return 'blog-single-select';
    }

    public function collect(CmsSlotEntity $slot, ResolverContext $resolverContext): ?CriteriaCollection
    {
        /* get the config from the element */
        $config = $slot->getFieldConfig();

        $criteria = new Criteria();

        $criteria->addFilter(
            new EqualsFilter('id', $config->get('blogEntry')->getValue())
        );

        $criteria->addAssociations(['author', 'author.media', 'author.blogs', 'blogCategories']);

        $criteriaCollection = new CriteriaCollection();

        $criteriaCollection->add(
            'sas_blog_single_select',
            BlogEntriesDefinition::class,
            $criteria
        );

        return $criteriaCollection;
    }

    public function enrich(CmsSlotEntity $slot, ResolverContext $resolverContext, ElementDataCollection $result): void
    {
        if(!is_null($result->get('sas_blog_single_select')->first())) {
            $slot->setData($result->get('sas_blog_single_select')->first());
        }
    }
}
