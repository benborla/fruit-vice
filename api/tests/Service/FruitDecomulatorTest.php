<?php

// tests/Service/FruitDecomulatorTest.php

namespace App\Tests\Service;

use App\Service\FruitDecomulator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Doctrine\ORM\Mapping\ClassMetadata;

class FruitDecomulatorTest extends KernelTestCase
{
    private const TABLE_NAME = 'fruits';

    public function testInvoke()
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $classMetadata = $this->createMock(ClassMetadata::class);
        $classMetadata->expects($this->any())
            ->method('getTableName')
            ->willReturn(self::TABLE_NAME);

        $entityManager->expects($this->any())
            ->method('getClassMetadata')
            ->willReturn($classMetadata);

        $connection = $this->createMock(\Doctrine\DBAL\Connection::class);
        $connection->expects($this->any())
            ->method('getDatabasePlatform')
            ->willReturn($this->createMock(\Doctrine\DBAL\Platforms\AbstractPlatform::class));

        $entityManager->expects($this->any())
            ->method('getConnection')
            ->willReturn($connection);

        $cache = $this->createMock(FilesystemAdapter::class);

        // Set up an expectation that the deleteItem method will be called at least once
        $cache->expects($this->any())
            ->method('deleteItem')
            ->with('api.fruits.*');

        $fruitDecomulator = new FruitDecomulator($entityManager, $cache);

        // Call the __invoke method
        $fruitDecomulator->__invoke();

        // @INFO: Make sure that it has cleared the cache
        $this->assertEquals($cache->hasItem('api.fruits.*'), false);
    }
}
