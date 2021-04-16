<?php

namespace App\Command;

use App\Messenger\Message\FinishedIntervals;
use App\Repository\IntervalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class MarkFinishedIntervalsCommand extends Command
{
    private $messageBus;
    private $repository;
    private $entityManager;

    public function __construct(
        MessageBusInterface $messageBus,
        IntervalRepository $repository,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct();
        $this->messageBus = $messageBus;
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setName('app:interval:mark-finished')
            ->setDescription('Mark overdone intervals as finished');
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Looking for intervals</info>');
        $output->writeln('');
        $intervals = $this->repository->findOverdoneIntervals();
        $output->writeln(sprintf('Found %d intervals', count($intervals)));
        $output->writeln('Dispatching message');
        $this->messageBus->dispatch(new FinishedIntervals(...$intervals));
        $output->writeln('');
        $output->writeln('<info>Done</info>');
        $this->entityManager->flush();

        return Command::SUCCESS;
    }

}
