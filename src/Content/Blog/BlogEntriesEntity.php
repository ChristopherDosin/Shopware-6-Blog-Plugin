<?php declare(strict_types=1);
namespace Sas\BlogModule\Content\Blog;

use Sas\BlogModule\Content\Blog\BlogTranslation\BlogTranslationCollection;
use Sas\BlogModule\Content\BlogAuthor\BlogAuthorEntity;
use Sas\BlogModule\Content\BlogCategory\BlogCategoryCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class BlogEntriesEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var int
     */
    protected $active;

    /**
     * @var BlogTranslationCollection|null
     */
    protected $translations;

    /**
     * @var BlogCategoryCollection|null
     */
    protected $blogCategories;

    /**
     * @var string
     */
    protected $authorId;

    /**
     * @var BlogAuthorEntity|null
     */
    protected $author;

    public function getAuthorId(): string
    {
        return $this->authorId;
    }

    public function setAuthorId(string $authorId): void
    {
        $this->authorId = $authorId;
    }

    public function getAuthor(): ?BlogAuthorEntity
    {
        return $this->author;
    }

    public function setAuthor(BlogAuthorEntity $author): void
    {
        $this->author = $author;
    }

    public function getActive(): int
    {
        return $this->active;
    }

    public function setActive(int $active): void
    {
        $this->active = $active;
    }

    public function getTranslations(): ?BlogTranslationCollection
    {
        return $this->translations;
    }

    public function setTranslations(?BlogTranslationCollection $translations): void
    {
        $this->translations = $translations;
    }

    public function getBlogCategories(): ?BlogCategoryCollection
    {
        return $this->blogCategories;
    }

    public function setBlogCategories(BlogCategoryCollection $blogCategories): void
    {
        $this->blogCategories = $blogCategories;
    }
}
