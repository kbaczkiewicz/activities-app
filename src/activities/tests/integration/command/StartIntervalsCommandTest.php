<?php

namespace integration\command;

use App\Repository\IntervalRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class StartIntervalsCommandTest extends KernelTestCase
{
    public function testWF31ItStartsIntervals()
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $this->assertCount(2, static::$container->get(IntervalRepository::class)->getIntervalsToStart());

        $command = $application->find('app:interval:start');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $this->assertCount(0, static::$container->get(IntervalRepository::class)->getIntervalsToStart());
    }
}
