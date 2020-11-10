<?php declare(strict_types=1);
namespace Sas\BlogModule\Migration;

use Doctrine\DBAL\Connection;
use Sas\BlogModule\Content\Blog\Aggregate\BlogCategoryMappingDefinition;
use Sas\BlogModule\Content\Blog\BlogEntriesDefinition;
use Sas\BlogModule\Content\BlogCategory\BlogCategoryDefinition;
use Sas\BlogModule\Content\BlogCategory\BlogCategoryTranslation\BlogCategoryTranslationDefinition;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\DataAbstractionLayer\Doctrine\MultiInsertQueryQueue;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Migration\Traits\ImportTranslationsTrait;
use Shopware\Core\Migration\Traits\Translations;

class Migration1604520733DefaultBlogCategorySeeder extends MigrationStep
{
    use ImportTranslationsTrait;

    /**
     * @var string
     */
    private $deDeLanguageId;

    public function getCreationTimestamp(): int
    {
        return 1604520733;
    }

    public function update(Connection $connection): void
    {
        $categoryId = $this->createRootCategory($connection);

        $translations = new Translations(
            [
                'sas_blog_category_id' => $categoryId,
                'name'                 => 'Allgemeines',
            ],
            [
                'sas_blog_category_id' => $categoryId,
                'name'                 => 'General',
            ]
        );

        $this->importTranslation(BlogCategoryTranslationDefinition::ENTITY_NAME, $translations, $connection);

        $blogs = $connection->fetchAll('SELECT id FROM ' . BlogEntriesDefinition::ENTITY_NAME) ?? [];

        $queue = new MultiInsertQueryQueue($connection, 50);

        foreach ($blogs as $insert) {
            $insert['sas_blog_category_id'] = $categoryId;
            $insert['sas_blog_entries_id'] = $insert['id'];
            unset($insert['id']);

            $queue->addInsert(BlogCategoryMappingDefinition::ENTITY_NAME, $insert);
        }

        $queue->execute();
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }

    private function createRootCategory(Connection $connection): string
    {
        $id = Uuid::randomBytes();

        $connection->insert(BlogCategoryDefinition::ENTITY_NAME, ['id' => $id, 'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT)]);

        return $id;
    }
}
