<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\FruitAggregator;
use App\Service\FruitDecomulator;

#[AsCommand(
    name: 'fruits:fetch',
    description: 'Fetch data from api https://fruityvice.com/api/fruit/*',
)]
class FruitsFetchCommand extends Command
{
    public function __construct(
        private FruitAggregator $aggregator,
        private FruitDecomulator $decomulator,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption(
                'truncate',
                ['--truncate', 't'],
                InputOption::VALUE_OPTIONAL,
                'Clear Fruit data before adding'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $truncate = 'false' === $input->getOption('truncate') ? false : true;

        if ($truncate) {
            $io->info('Clearing Fruit table');
            $this->decomulator->__invoke();
        }

        $io->comment('Processing...');
        // @INFO: Fake step loading
        $io->createProgressBar(100);
        $io->progressStart();
        $io->progressAdvance(50);

        // @INFO: Process the synchronization of API data to DB
        $this->aggregator->sync();

        // @INFO: Fake progress iterate
        $io->progressAdvance(50);
        $io->progressFinish();

        $io->success('Done!');

        return Command::SUCCESS;
    }
}
