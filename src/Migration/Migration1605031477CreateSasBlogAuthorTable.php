<?php declare(strict_types=1);
namespace Sas\BlogModule\Migration;

use Doctrine\DBAL\Connection;
use Sas\BlogModule\SasBlogModule;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

class Migration1605031477CreateSasBlogAuthorTable extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1605031477;
    }

    public function update(Connection $connection): void
    {
        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `sas_blog_author` (
                `id` BINARY(16) NOT NULL,
                `salutation_id` BINARY(16) NOT NULL,
                `media_id` BINARY(16) NULL,
                `first_name` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `last_name` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `display_name` VARCHAR(255) COLLATE utf8mb4_unicode_ci NULL,
                `email` VARCHAR(254) COLLATE utf8mb4_unicode_ci NOT NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`),
                KEY `fk.sas_blog_author.salutation_id` (`salutation_id`),
                KEY `fk.sas_blog_author.media_id` (`media_id`),
                CONSTRAINT `fk.sas_blog_author.salutation_id` FOREIGN KEY (`salutation_id`)
                    REFERENCES `salutation` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
                CONSTRAINT `fk.sas_blog_author.media_id` FOREIGN KEY (`media_id`)
                    REFERENCES `media` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `sas_blog_author_translation` (
                `description` LONGTEXT NULL,
                `custom_fields` JSON NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                `sas_blog_author_id` BINARY(16) NOT NULL,
                `language_id` BINARY(16) NOT NULL,
                PRIMARY KEY (`sas_blog_author_id`,`language_id`),
                CONSTRAINT `json.sas_blog_author_translation.custom_fields` CHECK (JSON_VALID(`custom_fields`)),
                KEY `fk.sas_blog_author_translation.sas_blog_author_id` (`sas_blog_author_id`),
                KEY `fk.sas_blog_author_translation.language_id` (`language_id`),
                CONSTRAINT `fk.sas_blog_author_translation.sas_blog_author_id` FOREIGN KEY (`sas_blog_author_id`) REFERENCES `sas_blog_author` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.sas_blog_author_translation.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $notSpecified = $connection->fetchColumn("SELECT id from salutation WHERE salutation_key = 'not_specified' LIMIT 1");

        $defaultAuthorId = $connection->fetchColumn('SELECT id from sas_blog_author LIMIT 1');

        if (empty($defaultAuthorId)) {
            $defaultAuthorId = Uuid::fromHexToBytes(SasBlogModule::ANONYMOUS_AUTHOR_ID);

            $connection->insert('sas_blog_author', [
                'id'            => $defaultAuthorId,
                'first_name'    => 'N/A',
                'last_name'     => 'N/A',
                'salutation_id' => $notSpecified,
                'display_name'  => 'Anonymous',
                'email'         => 'N/A',
                'created_at'    => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
            ]);
        }

        $connection->executeUpdate("
            ALTER TABLE sas_blog_entries ADD COLUMN author_id BINARY(16) NOT NULL DEFAULT '" . $defaultAuthorId . "' AFTER active;
        ");
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
