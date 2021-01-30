<?php declare(strict_types=1);
namespace Sas\BlogModule\Content\Blog;

use DateTimeInterface;
use Sas\BlogModule\Content\Blog\BlogTranslation\BlogTranslationCollection;
use Sas\BlogModule\Content\BlogAuthor\BlogAuthorEntity;
use Sas\BlogModule\Content\BlogCategory\BlogCategoryCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class BlogEntriesEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var bool
     */
    protected $active;

    /**
     * @var bool
     */
    protected $detailTeaserImage;

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

    /**
     * @var DateTimeInterface
     */
    protected $createdAt;

    /**
     * @var DateTimeInterface
     */
    protected $updatedAt;

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

    public function getActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getDetailTeaserImage(): bool
    {
        return $this->detailTeaserImage;
    }

    public function setDetailTeaserImage(bool $detailTeaserImage): void
    {
        $this->detailTeaserImage = $detailTeaserImage;
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

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     */
    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTimeInterface
     */
    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeInterface $updatedAt
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
