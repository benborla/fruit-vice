<?php

// tests/Command/FruitsFetchCommandTest.php

namespace App\Tests\Command;

use App\Command\FruitsFetchCommand;
use App\Service\FruitAggregator;
use App\Service\FruitDecomulator;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class FruitsFetchCommandTest extends KernelTestCase
{
    private $aggregator;
    private $decomulator;
    private $application;
    private $command;

    public function setUp(): void
    {
    // Mock the services
        $this->aggregator = $this->createMock(FruitAggregator::class);
        $this->decomulator = $this->createMock(FruitDecomulator::class);
        $this->command = new FruitsFetchCommand($this->aggregator, $this->decomulator);

        $this->application = new Application(self::bootKernel());
        $this->application->add($this->command);
        $this->command = $this->application->find('fruits:fetch');
    }

    public function testFruitsFetchCommandDefault()
    {
        $commandTester = new CommandTester($this->command);

        // @INFO: Run the command with default option
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
        $this->aggregator = null;
        $this->decomulator = null;
    }
}
