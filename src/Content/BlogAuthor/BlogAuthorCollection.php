<?php declare(strict_types=1);
namespace Sas\BlogModule\Content\BlogAuthor;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void              add(BlogAuthorEntity $entity)
 * @method void              set(string $key, BlogAuthorEntity $entity)
 * @method BlogAuthorEntity[]    getIterator()
 * @method BlogAuthorEntity[]    getElements()
 * @method BlogAuthorEntity|null get(string $key)
 * @method BlogAuthorEntity|null first()
 * @method BlogAuthorEntity|null last()
 */
class BlogAuthorCollection extends EntityCollection
{
    /**
     * @return string
     */
    protected function getExpectedClass(): string
    {
        return BlogAuthorEntity::class;
    }

    public function getApiAlias(): string
    {
        return 'sas_blog_author_collection';
    }
}
