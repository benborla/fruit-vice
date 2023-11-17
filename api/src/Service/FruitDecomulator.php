<?php

namespace App\Service;

use App\Entity\Fruit;
use Doctrine\ORM\EntityManagerInterface;

/**
 * This service class will truncate the `fruits` table
 */
class FruitDecomulator
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    /**
     * Truncate the `fruits` table
     *
     * @return void
     */
    public function __invoke(): void
    {
        $connection = $this->em->getConnection();
        $platform = $connection->getDatabasePlatform();
        $table = $this->em->getClassMetadata(Fruit::class)->getTableName();
        $connection->executeStatement($platform->getTruncateTableSQL($table, true));
    }
}
