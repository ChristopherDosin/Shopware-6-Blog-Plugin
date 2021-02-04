<?php declare(strict_types=1);

namespace Sas\BlogModule\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1612160298CreatePubslihedDateColumn extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1612160298;
    }

    public function update(Connection $connection): void
    {
        $connection->executeUpdate('
            ALTER TABLE
              sas_blog_entries
            ADD
              COLUMN published_at DATETIME(3) NOT NULL
            AFTER
              created_at
        ');

        $connection->executeUpdate('
            UPDATE sas_blog_entries SET published_at = created_at
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
