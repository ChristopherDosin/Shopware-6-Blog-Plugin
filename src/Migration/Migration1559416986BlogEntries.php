<?php declare(strict_types=1);
namespace Sas\BlogModule\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

class Migration1559416986BlogEntries extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1559416986;
    }

    public function update(Connection $connection): void
    {
        $connection->executeQuery(
            '
            CREATE TABLE IF NOT EXISTS `sas_blog_entries` (
            `id` BINARY(16) NOT NULL,
            `active` TINYINT DEFAULT 0,
            `created_at` DATETIME(3) NOT NULL,
            `updated_at` DATETIME(3) NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        '
        );

        $connection->executeQuery(
            '
            CREATE TABLE IF NOT EXISTS `sas_blog_entries_translation` (
            `sas_blog_entries_id` BINARY(16) NOT NULL,
            `language_id` BINARY(16) NOT NULL,
            `title` VARCHAR(255) NOT NULL,
            `slug` VARCHAR(255) NOT NULL,
            `teaser` VARCHAR(255) NULL,
            `meta_title` VARCHAR(255) NULL,
            `meta_description` VARCHAR(255) NULL,
            `content` JSON NULL,
            `created_at` DATETIME(3) NOT NULL,
            `updated_at` DATETIME(3) NULL,
            PRIMARY KEY (`sas_blog_entries_id`, `language_id`),
            CONSTRAINT `fk.sas_blog_entries_translation.language_id` FOREIGN KEY (`language_id`)
                REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT `fk.sas_blog_entries_translation.sas_blog_entries_id` FOREIGN KEY (`sas_blog_entries_id`)
                REFERENCES `sas_blog_entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        '
        );

        $connection->executeUpdate('
            INSERT INTO `seo_url_template` (`id`, `sales_channel_id`, `route_name`, `entity_name`, `template`, `is_valid`, `custom_fields`, `created_at`, `updated_at`)
            VALUES (:id, NULL, :routeName, :entityName, :template, 1, NULL, :createdAt, NULL);
        ', [
            'id'         => Uuid::randomBytes(),
            'routeName'  => 'sas.frontend.blog.detail',
            'entityName' => 'sas_blog_entries',
            'template'   => 'blog/{{ entry.title|lower }}',
            'createdAt'  => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
