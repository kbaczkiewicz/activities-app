<?php

namespace App\Command;

use App\Messenger\Message\CreatedActivitiesToStart;
use App\Repository\ActivityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class StartActivitiesCommand extends Command
{
    private $repository;
    private $messageBus;
    private $entityManager;

    public function __construct(
        ActivityRepository $repository,
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
            ->setName('app:activity:start')
            ->setDescription('Mark created activities as pending');
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Mark failed activities command</info>');
        $output->writeln('');
        $output->writeln('Looking for activities');
        $activities = $this->repository->findActivitiesToStart();
        $output->writeln(sprintf('Found %d activities', count($activities)));
        $output->writeln('Dispatching message');
        $this->messageBus->dispatch(new CreatedActivitiesToStart(...$activities));
        $output->writeln('');
        $output->writeln('<info>Done</info>');
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
