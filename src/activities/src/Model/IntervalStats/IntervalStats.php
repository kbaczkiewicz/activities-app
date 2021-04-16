<?php

namespace App\Model\IntervalStats;

class IntervalStats implements \JsonSerializable
{
    private $completedActivitiesAmount;
    private $failedActivitiesAmount;

    public function __construct(int $completedActivitiesAmount, int $failedActivitiesAmount)
    {
        $this->completedActivitiesAmount = $completedActivitiesAmount;
        $this->failedActivitiesAmount = $failedActivitiesAmount;
    }

    public function jsonSerialize(): array
    {
        return [
            'completedAmount' => $this->completedActivitiesAmount,
            'failedAmount' => $this->failedActivitiesAmount,
            'completedPercent' => 0 === ($this->failedActivitiesAmount + $this->completedActivitiesAmount)
                ? 0
                : $this->completedActivitiesAmount / ($this->failedActivitiesAmount + $this->completedActivitiesAmount),
        ];
    }
}
