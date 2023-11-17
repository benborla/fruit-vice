<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\Fruit;
use Doctrine\ORM\EntityManagerInterface;

#[AsCommand(
    name: 'fruits:fetch',
    description: 'Fetch data from api https://fruityvice.com/api/fruit/*',
)]
class FruitsFetchCommand extends Command
{
    public function __construct(private EntityManagerInterface $em)
    {
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
        $truncate =  $input->getOption('truncate') === 'false' ? false : true;

        if ($truncate) {
            $io->info('Clearing Fruit table');
            // @TODO: Improve this, or move this to a service
            $connection = $this->em->getConnection();
            $platform = $connection->getDatabasePlatform();
            $table = $this->em->getClassMetadata(Fruit::class)->getTableName();
            $connection->executeStatement($platform->getTruncateTableSQL($table, true ));
        }

        $io->comment('Processing...');


        $io->success('Done!');

        return Command::SUCCESS;
    }
}
