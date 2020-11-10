<?php declare(strict_types=1);
namespace Sas\BlogModule\Content\BlogCategory;

use Sas\BlogModule\Content\BlogCategory\BlogCategoryTranslation\BlogCategoryTranslationCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Sas\BlogModule\Content\Blog\BlogEntriesCollection;

class BlogCategoryEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string|null
     */
    protected $parentId;

    /**
     * @var string|null
     */
    protected $afterCategoryId;

    /**
     * @var int|null
     */
    protected $level;

    /**
     * @var string|null
     */
    protected $path;

    /**
     * @var int|null
     */
    protected $childCount;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array|null
     */
    protected $customFields;

    /**
     * @var self|null
     */
    protected $parent;

    /**
     * @var BlogCategoryCollection|null
     */
    protected $children;

    /**
     * @var BlogCategoryTranslationCollection|null
     */
    protected $translations;

    /**
     * @var BlogEntriesCollection|null
     */
    protected $blogs;

    public function getParentId(): ?string
    {
        return $this->parentId;
    }

    public function setParentId(?string $parentId): void
    {
        $this->parentId = $parentId;
    }

    public function getAfterCategoryId(): ?string
    {
        return $this->afterCategoryId;
    }

    public function setAfterCategoryId(?string $afterCategoryId): void
    {
        $this->afterCategoryId = $afterCategoryId;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): void
    {
        $this->level = $level;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): void
    {
        $this->path = $path;
    }

    public function getChildCount(): ?int
    {
        return $this->childCount;
    }

    public function setChildCount(?int $childCount): void
    {
        $this->childCount = $childCount;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCustomFields(): ?array
    {
        return $this->customFields;
    }

    public function setCustomFields(?array $customFields): void
    {
        $this->customFields = $customFields;
    }

    public function getParent(): ?Entity
    {
        return $this->parent;
    }

    public function setParent(self $parent): void
    {
        $this->parent = $parent;
    }

    public function getChildren(): ?BlogCategoryCollection
    {
        return $this->children;
    }

    public function setChildren(BlogCategoryCollection $children): void
    {
        $this->children = $children;
    }

    public function getTranslations(): BlogCategoryTranslationCollection
    {
        return $this->translations;
    }

    public function setTranslations(BlogCategoryTranslationCollection $translations): void
    {
        $this->translations = $translations;
    }

    public function getBlogs(): ?BlogEntriesCollection
    {
        return $this->blogs;
    }

    public function setBlogs(BlogEntriesCollection $blogs): void
    {
        $this->blogs = $blogs;
    }
}
