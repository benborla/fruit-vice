<?php

namespace App\Service;

use App\Entity\Fruit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

/**
 * This service class will truncate the `fruits` table
 */
class FruitDecomulator
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    /**
     * Truncate the `fruits` table
     *
     * @return void
     */
    public function __invoke(): void
    {
        // @INFO: Truncate table
        $connection = $this->em->getConnection();
        $platform = $connection->getDatabasePlatform();
        $table = $this->em->getClassMetadata(Fruit::class)->getTableName();
        // @INFO: Disable foreign key checking when truncating
        $connection->executeStatement("SET foreign_key_checks = 0;");
        $connection->executeStatement($platform->getTruncateTableSQL($table, true));
        // @INFO: Put back the original setting
        $connection->executeStatement("SET foreign_key_checks = 0;");

        // @INFO: Purge cache
        $cache = new FilesystemAdapter();
        $cache->deleteItem('api.fruits.*');
    }
}
