<?php

namespace integration\command;

use App\Repository\ActivityRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class MarkFailedActivitiesCommandTest extends KernelTestCase
{
    public function testWF33ItMarksOverdoneActivitiesAsFailed()
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $this->assertCount(8, static::$container->get(ActivityRepository::class)->findActivitiesToMarkAsFailed());

        $command = $application->find('app:activity:mark-failed');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $this->assertCount(0, static::$container->get(ActivityRepository::class)->findActivitiesToMarkAsFailed());
    }
}
