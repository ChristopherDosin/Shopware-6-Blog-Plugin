<?php declare(strict_types=1);
namespace Sas\BlogModule\Content\BlogCategory;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                add(BlogCategoryEntity $entity)
 * @method void                set(string $key, BlogCategoryEntity $entity)
 * @method BlogCategoryEntity[]    getIterator()
 * @method BlogCategoryEntity[]    getElements()
 * @method BlogCategoryEntity|null get(string $key)
 * @method BlogCategoryEntity|null first()
 * @method BlogCategoryEntity|null last()
 */
class BlogCategoryCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return BlogCategoryEntity::class;
    }

    public function getApiAlias(): string
    {
        return 'sas_blog_category_collection';
    }
}
