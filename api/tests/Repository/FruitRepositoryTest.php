<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Entity\Fruit;
use App\Repository\FruitRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FruitRepositoryTest extends KernelTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * Return the Fruit repository class.
     */
    private function getFruitRepository(): FruitRepository
    {
        return $this->entityManager
            ->getRepository(Fruit::class);
    }

    public function testFetchAll(): void
    {
        $this->assertIsArray($this->getFruitRepository()->findAll());
    }

    public function testVerifyFruitInstance(): void
    {
        $fruit = $this->getFruitRepository()->findOneBy(['id' => 1]);
        $this->assertInstanceOf($fruit::class, new Fruit());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // @INFO: doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
