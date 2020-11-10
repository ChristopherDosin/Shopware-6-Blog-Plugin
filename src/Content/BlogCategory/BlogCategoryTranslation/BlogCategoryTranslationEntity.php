<?php declare(strict_types=1);
namespace Sas\BlogModule\Content\BlogCategory\BlogCategoryTranslation;

use Sas\BlogModule\Content\BlogCategory\BlogCategoryEntity;
use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

class BlogCategoryTranslationEntity extends TranslationEntity
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array|null
     */
    protected $customFields;

    /**
     * @var string
     */
    protected $blogCategoryId;

    /**
     * @var BlogCategoryEntity|null
     */
    protected $blogCategory;

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

    public function getBlogCategoryId(): string
    {
        return $this->blogCategoryId;
    }

    public function setBlogCategoryId(string $blogCategoryId): void
    {
        $this->blogCategoryId = $blogCategoryId;
    }

    public function getBlogCategory(): ?BlogCategoryEntity
    {
        return $this->blogCategory;
    }

    public function setBlogCategory(BlogCategoryEntity $blogCategory): void
    {
        $this->blogCategory = $blogCategory;
    }
}
