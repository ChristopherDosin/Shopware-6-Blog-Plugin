<?php declare(strict_types=1);
namespace Sas\BlogModule\Content\Blog;

use Sas\BlogModule\Content\Blog\Aggregate\BlogCategoryMappingDefinition;
use Sas\BlogModule\Content\Blog\BlogTranslation\BlogTranslationDefinition;
use Sas\BlogModule\Content\BlogAuthor\BlogAuthorDefinition;
use Sas\BlogModule\Content\BlogCategory\BlogCategoryDefinition;
use Shopware\Core\Content\Media\MediaDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class BlogEntriesDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'sas_blog_entries';

    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return BlogEntriesEntity::class;
    }

    /**
     * @return string
     */
    public function getCollectionClass(): string
    {
        return BlogEntriesCollection::class;
    }

    /**
     * @return FieldCollection
     */
    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            new BoolField('active', 'active'),

            (new FkField('media_id', 'mediaId', MediaDefinition::class)),
            (new FkField('author_id', 'authorId', BlogAuthorDefinition::class))->addFlags(new Required()),
            (new OneToOneAssociationField('media', 'media_id', 'id', MediaDefinition::class, true)),

            new TranslatedField('title'),
            new TranslatedField('slug'),
            new TranslatedField('teaser'),
            new TranslatedField('metaTitle'),
            new TranslatedField('metaDescription'),
            new TranslatedField('content'),

            (new TranslationsAssociationField(BlogTranslationDefinition::class, 'sas_blog_entries_id'))->addFlags(new Required()),

            (new ManyToManyAssociationField('blogCategories', BlogCategoryDefinition::class, BlogCategoryMappingDefinition::class, 'sas_blog_entries_id', 'sas_blog_category_id'))->addFlags(new CascadeDelete()),
            new ManyToOneAssociationField('author', 'author_id', BlogAuthorDefinition::class, 'id', false),
        ]);
    }
}
