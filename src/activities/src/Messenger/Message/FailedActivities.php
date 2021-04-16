<?php


namespace App\Messenger\Message;


use App\Entity\Activity;

class FailedActivities
{
    /** @var Activity[] */
    private $activities;

    public function __construct(Activity ...$activities)
    {
        $this->activities = $activities;
    }

    /**
     * @return Activity[]
     */
    public function getActivities(): array
    {
        return $this->activities;
    }
}
