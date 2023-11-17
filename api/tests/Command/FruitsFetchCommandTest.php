<?php

// tests/Command/FruitsFetchCommandTest.php

namespace App\Tests\Command;

use App\Command\FruitsFetchCommand;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class FruitsFetchCommandTest extends KernelTestCase
{
    private const TABLE_NAME = 'fruits';
    private $entityManager;
    private $application;

    /**
     * @TODO: For improvement
     *
     * @return void
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->application = new Application($kernel);

        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $classMetadata = $this->createMock(ClassMetadata::class);
        $classMetadata->expects($this->any())
            ->method('getTableName')
            ->willReturn(self::TABLE_NAME);

        $this->entityManager->expects($this->any())
            ->method('getClassMetadata')
            ->willReturn($classMetadata);

        $connection = $this->createMock(\Doctrine\DBAL\Connection::class);
        $connection->expects($this->any())
            ->method('getDatabasePlatform')
            ->willReturn($this->createMock(\Doctrine\DBAL\Platforms\AbstractPlatform::class));

        $this->entityManager->expects($this->any())
            ->method('getConnection')
            ->willReturn($connection);

        $this->application->add(new FruitsFetchCommand($this->entityManager));
    }
    public function testFruitsFetchCommandDefault()
    {
        $command = $this->application->find('fruits:fetch');
        $commandTester = new CommandTester($command);

        // @INFO: Run the command with the `--truncate` option
        $commandTester->execute([]);

        // @INFO: Run assertions
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Processing...', $output);
        $this->assertStringContainsString('Done!', $output);
        $this->assertEquals(0, $commandTester->getStatusCode());
    }

    public function testFruitsFetchCommandWithTruncate()
    {
        $command = $this->application->find('fruits:fetch');
        $commandTester = new CommandTester($command);

        // @INFO: Run the command with the `--truncate` option
        $commandTester->execute(['--truncate' => true]);

        // @INFO: Run assertions
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Clearing Fruit table', $output);
        $this->assertStringContainsString('Processing...', $output);
        $this->assertStringContainsString('Done!', $output);
        $this->assertEquals(0, $commandTester->getStatusCode());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // @INFO: doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
