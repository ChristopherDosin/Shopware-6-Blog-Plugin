<?php declare(strict_types=1);
namespace Sas\BlogModule\Content\BlogAuthor;

use Sas\BlogModule\Content\Blog\BlogEntriesCollection;
use Sas\BlogModule\Content\BlogAuthor\BlogAuthorTranslation\BlogAuthorTranslationCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\System\Salutation\SalutationEntity;

class BlogAuthorEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string|null
     */
    protected $displayName;

    /**
     * @var string
     */
    protected $salutationId;

    /**
     * @var SalutationEntity|null
     */
    protected $salutation;

    /**
     * @var BlogAuthorTranslationCollection|null
     */
    protected $translations;

    /**
     * @var BlogEntriesCollection|null
     */
    protected $blogs;

    public function getTranslations(): ?BlogAuthorTranslationCollection
    {
        return $this->translations;
    }

    public function setTranslations(BlogAuthorTranslationCollection $translations): void
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

    public function getSalutation(): ?SalutationEntity
    {
        return $this->salutation;
    }

    public function setSalutation(SalutationEntity $salutation): void
    {
        $this->salutation = $salutation;
    }

    public function getSalutationId(): string
    {
        return $this->salutationId;
    }

    public function setSalutationId(string $salutationId): void
    {
        $this->salutationId = $salutationId;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(?string $displayName): void
    {
        $this->displayName = $displayName;
    }

    public function getTranslated(): array
    {
        $translated = parent::getTranslated();

        $translated['name'] = $this->getFirstName() . ' ' . $this->getLastName();
        return $translated;
    }
}
