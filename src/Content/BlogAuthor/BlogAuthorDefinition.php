<?php declare(strict_types=1);
namespace Sas\BlogModule\Content\BlogAuthor;

use Sas\BlogModule\Content\Blog\BlogEntriesDefinition;
use Sas\BlogModule\Content\BlogAuthor\BlogAuthorTranslation\BlogAuthorTranslationDefinition;
use Shopware\Core\Content\Media\MediaDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\SearchRanking;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\System\Salutation\SalutationDefinition;

class BlogAuthorDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'sas_blog_author';

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
        return BlogAuthorEntity::class;
    }

    /**
     * @return string
     */
    public function getCollectionClass(): string
    {
        return BlogAuthorCollection::class;
    }

    /**
     * @return FieldCollection
     */
    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),

            new FkField('media_id', 'mediaId', MediaDefinition::class),
            (new FkField('salutation_id', 'salutationId', SalutationDefinition::class))->addFlags(new Required()),

            (new StringField('first_name', 'firstName'))->addFlags(new Required(), new SearchRanking(SearchRanking::MIDDLE_SEARCH_RANKING)),
            (new StringField('last_name', 'lastName'))->addFlags(new Required(), new SearchRanking(SearchRanking::MIDDLE_SEARCH_RANKING)),
            (new StringField('email', 'email'))->addFlags(new Required(), new SearchRanking(SearchRanking::HIGH_SEARCH_RANKING)),
            (new StringField('display_name', 'displayName'))->addFlags(new SearchRanking(SearchRanking::HIGH_SEARCH_RANKING)),

            (new OneToOneAssociationField('media', 'media_id', 'id', MediaDefinition::class, true)),

            new TranslatedField('description'),
            new TranslatedField('customFields'),

            new TranslationsAssociationField(BlogAuthorTranslationDefinition::class, 'sas_blog_author_id'),

            (new OneToManyAssociationField('blogs', BlogEntriesDefinition::class, 'author_id', 'id'))->addFlags(new CascadeDelete()),
            new ManyToOneAssociationField('salutation', 'salutation_id', SalutationDefinition::class, 'id', false),
        ]);
    }
}
