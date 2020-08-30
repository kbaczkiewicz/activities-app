<?php


namespace App\Command;


use App\Messenger\Message\FailedActivities;
use App\Repository\ActivityRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class MarkFailedActivitiesCommand extends Command
{
    private $repository;
    private $messageBus;

    public function __construct(ActivityRepository $repository, MessageBusInterface $messageBus)
    {
        $this->repository = $repository;
        $this->messageBus = $messageBus;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:activity:mark-failed')
            ->setDescription('Mark not done in time activities as failed');
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Mark failed activities command</info>');
        $output->writeln('');
        $output->writeln('Looking for activities');
        $activities = $this->repository->getActivitiesToMarkAsFailed();
        $output->writeln(sprintf('Found %d activities', count($activities)));
        $output->writeln('Dispatching message');
        $this->messageBus->dispatch(new FailedActivities(...$activities));
        $output->writeln('');
        $output->writeln('<info>Done</info>');

        return Command::SUCCESS;
    }
}
