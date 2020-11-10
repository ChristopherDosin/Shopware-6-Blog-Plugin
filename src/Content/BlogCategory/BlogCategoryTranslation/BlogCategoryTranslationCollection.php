<?php declare(strict_types=1);
namespace Sas\BlogModule\Content\BlogCategory\BlogCategoryTranslation;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                add(BlogCategoryTranslationEntity $entity)
 * @method void                set(string $key, BlogCategoryTranslationEntity $entity)
 * @method BlogCategoryTranslationEntity[]    getIterator()
 * @method BlogCategoryTranslationEntity[]    getElements()
 * @method BlogCategoryTranslationEntity|null get(string $key)
 * @method BlogCategoryTranslationEntity|null first()
 * @method BlogCategoryTranslationEntity|null last()
 */
class BlogCategoryTranslationCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return BlogCategoryTranslationEntity::class;
    }

    public function getApiAlias(): string
    {
        return 'sas_blog_category_translation_collection';
    }
}
