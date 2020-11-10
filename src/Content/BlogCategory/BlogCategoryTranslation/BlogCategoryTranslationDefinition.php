<?php declare(strict_types=1);
namespace Sas\BlogModule\Content\BlogCategory\BlogCategoryTranslation;

use Sas\BlogModule\Content\BlogCategory\BlogCategoryDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\Routing\Annotation\Entity;
use Shopware\Core\Framework\Struct\Collection;

class BlogCategoryTranslationDefinition extends EntityTranslationDefinition
{
    public const ENTITY_NAME = 'sas_blog_category_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return Entity::class;
    }

    public function getCollectionClass(): string
    {
        return Collection::class;
    }

    protected function getParentDefinitionClass(): string
    {
        return BlogCategoryDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name', 'name'))->addFlags(new Required()),
            new CustomFields(),
        ]);
    }
}
