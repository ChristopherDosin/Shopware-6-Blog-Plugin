<?php declare(strict_types=1);
namespace Sas\BlogModule\Content\Blog\Aggregate;

use Sas\BlogModule\Content\Blog\BlogEntriesDefinition;
use Sas\BlogModule\Content\BlogCategory\BlogCategoryDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\MappingEntityDefinition;

class BlogCategoryMappingDefinition extends MappingEntityDefinition
{
    public const ENTITY_NAME = 'sas_blog_blog_category';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new FkField('sas_blog_entries_id', 'blogId', BlogEntriesDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new FkField('sas_blog_category_id', 'blogCategoryId', BlogCategoryDefinition::class))->addFlags(new PrimaryKey(), new Required()),

            new ManyToOneAssociationField('blog', 'sas_blog_entries_id', BlogEntriesDefinition::class, 'id', false),
            new ManyToOneAssociationField('blogCategory', 'sas_blog_category_id', BlogCategoryDefinition::class, 'id', false),
        ]);
    }
}
