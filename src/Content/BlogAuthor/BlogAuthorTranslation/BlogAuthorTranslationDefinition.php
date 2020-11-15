<?php declare(strict_types=1);
namespace Sas\BlogModule\Content\BlogAuthor\BlogAuthorTranslation;

use Sas\BlogModule\Content\BlogAuthor\BlogAuthorDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use Shopware\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class BlogAuthorTranslationDefinition extends EntityTranslationDefinition
{
    public function getEntityName(): string
    {
        return 'sas_blog_author_translation';
    }

    public function getCollectionClass(): string
    {
        return BlogAuthorTranslationCollection::class;
    }

    public function getEntityClass(): string
    {
        return BlogAuthorTranslationEntity::class;
    }

    protected function getParentDefinitionClass(): string
    {
        return BlogAuthorDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            new LongTextField('description', 'description'),
            new CustomFields(),
        ]);
    }
}
