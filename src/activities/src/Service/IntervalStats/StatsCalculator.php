<?php

namespace App\Service\IntervalStats;

use App\Entity\Interval;
use App\Repository\ActivityRepository;
use App\Model\IntervalStats\IntervalStats;

class StatsCalculator
{
    private $activityRepository;

    public function __construct(ActivityRepository $activityRepository)
    {
        $this->activityRepository = $activityRepository;
    }

    public function calculate(Interval $interval)
    {
        return new IntervalStats(
            $this->activityRepository->findBy(['interval' => $interval, 'status' => 'completed']),
            $this->activityRepository->findBy(['interval' => $interval, 'status' => 'failed']),
            );
    }
}
