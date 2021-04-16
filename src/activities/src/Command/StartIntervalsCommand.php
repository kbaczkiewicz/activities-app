<?php

namespace App\Command;

use App\Messenger\Message\CreatedIntervalsToStart;
use App\Repository\IntervalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class StartIntervalsCommand extends Command
{
    private $repository;
    private $messageBus;
    private $entityManager;

    public function __construct(
        IntervalRepository $repository,
        MessageBusInterface $messageBus,
        EntityManagerInterface $entityManager
    )
    {
        $this->repository = $repository;
        $this->messageBus = $messageBus;
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setName('app:interval:start')
            ->setDescription('Mark created intervals as started');
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Mark failed activities command</info>');
        $output->writeln('');
        $output->writeln('Looking for activities');
        $intervals = $this->repository->getIntervalsToStart();
        $output->writeln(sprintf('Found %d activities', count($intervals)));
        $output->writeln('Dispatching message');
        $this->messageBus->dispatch(new CreatedIntervalsToStart(...$intervals));
        $output->writeln('');
        $output->writeln('<info>Done</info>');
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
