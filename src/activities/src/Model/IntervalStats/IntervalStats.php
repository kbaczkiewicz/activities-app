<?php

namespace App\Model\IntervalStats;

use App\Entity\Activity;

class IntervalStats implements \JsonSerializable
{
    private $completedActivities;
    private $failedActivities;

    /**
     * @param Activity[] $completedActivities
     * @param Activity[] $failedActivities
     */
    public function __construct(array $completedActivities, array $failedActivities)
    {
        $this->completedActivities = $completedActivities;
        $this->failedActivities = $failedActivities;
    }

    public function jsonSerialize(): array
    {
        return [
            'activities' =>
                array_map(
                    function (Activity $a) {
                        return [
                            'name' => $a->getName(),
                            'dateStart' => $a->getDateStart()->format('Y-m-d'),
                            'status' => $a->getStatus(),
                        ];
                    },
                    array_merge($this->failedActivities, $this->completedActivities)
                ),
            'completed' => count($this->completedActivities),
            'failed' => count($this->failedActivities),
            'completedPercent' => 0 === (count($this->failedActivities) + count(
                    $this->completedActivities
                )) ? 0 : count($this->completedActivities) / (count($this->failedActivities) + count(
                        $this->completedActivities
                    )),
        ];
    }
}
