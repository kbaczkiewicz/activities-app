<?php

namespace App\Messenger\Message;

use App\Entity\Interval;

class IntervalPostFinishStats
{
    private $interval;

    public function __construct(Interval $interval)
    {
        $this->interval = $interval;
    }

    public function getInterval(): Interval
    {
        return $this->interval;
    }
}
