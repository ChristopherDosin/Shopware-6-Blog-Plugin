<?php declare(strict_types=1);
namespace Sas\BlogModule\Content\BlogCategory;

use Sas\BlogModule\Content\Blog\Aggregate\BlogCategoryMappingDefinition;
use Sas\BlogModule\Content\Blog\BlogEntriesDefinition;
use Sas\BlogModule\Content\BlogCategory\BlogCategoryTranslation\BlogCategoryTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ChildCountField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ChildrenAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\SearchRanking;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ParentAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ParentFkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TreeLevelField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TreePathField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class BlogCategoryDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'sas_blog_category';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return BlogCategoryCollection::class;
    }

    public function getEntityClass(): string
    {
        return BlogCategoryEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),

            new ParentFkField(self::class),

            new FkField('after_category_id', 'afterCategoryId', self::class),

            new TreeLevelField('level', 'level'),
            new TreePathField('path', 'path'),
            new ChildCountField(),

            (new TranslatedField('name'))->addFlags(new SearchRanking(SearchRanking::HIGH_SEARCH_RANKING)),
            new TranslatedField('customFields'),

            new ParentAssociationField(self::class, 'id'),
            new ChildrenAssociationField(self::class),

            (new TranslationsAssociationField(BlogCategoryTranslationDefinition::class, 'sas_blog_category_id'))->addFlags(new Required()),

            (new ManyToManyAssociationField('blogs', BlogEntriesDefinition::class, BlogCategoryMappingDefinition::class, 'sas_blog_category_id', 'sas_blog_entries_id'))->addFlags(new CascadeDelete()),
        ]);
    }
}
