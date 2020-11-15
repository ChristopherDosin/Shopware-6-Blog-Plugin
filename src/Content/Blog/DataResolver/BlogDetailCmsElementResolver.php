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

class BlogDetailCmsElementResolver extends AbstractCmsElementResolver
{
    public function getType(): string
    {
        return 'blog-detail';
    }

    public function collect(CmsSlotEntity $slot, ResolverContext $resolverContext): ?CriteriaCollection
    {
        /* get the config from the element */
        $config = $slot->getFieldConfig();

        $criteria = new Criteria();

        $criteria->addFilter(
            new EqualsFilter('active', true),
            new EqualsFilter('id', $resolverContext->getRequest()->get('articleId'))
        );
        $criteria->addAssociations(['author', 'blogCategories']);

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
        $slot->setData($result->get('sas_blog')->first());
    }
}
