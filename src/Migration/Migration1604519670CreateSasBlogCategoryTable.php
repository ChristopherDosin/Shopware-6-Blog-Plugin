<?php declare(strict_types=1);
namespace Sas\BlogModule\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1604519670CreateSasBlogCategoryTable extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1604519670;
    }

    public function update(Connection $connection): void
    {
        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `sas_blog_category` (
                `id` BINARY(16) NOT NULL,
                `parent_id` BINARY(16) NULL,
                `after_category_id` BINARY(16) NULL,
                `level` INT(11) NULL,
                `path` LONGTEXT NULL,
                `child_count` INT(11) NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                PRIMARY KEY (`id`),
                KEY `fk.sas_blog_category.parent_id` (`parent_id`),
                CONSTRAINT `fk.sas_blog_category.parent_id` FOREIGN KEY (`parent_id`) REFERENCES `sas_blog_category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `sas_blog_category_translation` (
                `name` VARCHAR(255) NOT NULL,
                `custom_fields` JSON NULL,
                `created_at` DATETIME(3) NOT NULL,
                `updated_at` DATETIME(3) NULL,
                `sas_blog_category_id` BINARY(16) NOT NULL,
                `language_id` BINARY(16) NOT NULL,
                PRIMARY KEY (`sas_blog_category_id`,`language_id`),
                CONSTRAINT `json.sas_blog_category_translation.custom_fields` CHECK (JSON_VALID(`custom_fields`)),
                KEY `fk.sas_blog_category_translation.sas_blog_category_id` (`sas_blog_category_id`),
                KEY `fk.sas_blog_category_translation.language_id` (`language_id`),
                CONSTRAINT `fk.sas_blog_category_translation.sas_blog_category_id` FOREIGN KEY (`sas_blog_category_id`) REFERENCES `sas_blog_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.sas_blog_category_translation.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');

        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `sas_blog_blog_category` (
                `sas_blog_entries_id` BINARY(16) NOT NULL,
                `sas_blog_category_id` BINARY(16) NOT NULL,
                PRIMARY KEY (`sas_blog_entries_id`,`sas_blog_category_id`),
                KEY `fk.sas_blog_blog_category.sas_blog_entries_id` (`sas_blog_entries_id`),
                KEY `fk.sas_blog_blog_category.sas_blog_category_id` (`sas_blog_category_id`),
                CONSTRAINT `fk.sas_blog_blog_category.sas_blog_entries_id` FOREIGN KEY (`sas_blog_entries_id`) REFERENCES `sas_blog_entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT `fk.sas_blog_blog_category.sas_blog_category_id` FOREIGN KEY (`sas_blog_category_id`) REFERENCES `sas_blog_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
