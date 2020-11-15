<?php declare(strict_types=1);
namespace Sas\BlogModule\Content\BlogAuthor\BlogAuthorTranslation;

use Sas\BlogModule\Content\BlogAuthor\BlogAuthorEntity;
use Shopware\Core\Framework\DataAbstractionLayer\TranslationEntity;

class BlogAuthorTranslationEntity extends TranslationEntity
{
    /**
     * @var string
     */
    protected $blogAuthorId;

    /**
     * @var BlogAuthorEntity|null
     */
    protected $blogAuthor;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var array|null
     */
    protected $customFields;

    public function getBlogAuthorId(): string
    {
        return $this->blogAuthorId;
    }

    public function setBlogAuthorId(string $blogAuthorId): void
    {
        $this->blogAuthorId = $blogAuthorId;
    }

    public function getBlogAuthor(): ?BlogAuthorEntity
    {
        return $this->blogAuthor;
    }

    public function setBlogAuthor(BlogAuthorEntity $blogAuthor): void
    {
        $this->blogAuthor = $blogAuthor;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getCustomFields(): ?array
    {
        return $this->customFields;
    }

    public function setCustomFields(?array $customFields): void
    {
        $this->customFields = $customFields;
    }
}
