<?php

namespace App\Messenger\Message;

use App\Entity\Interval;

class CreatedIntervalsToStart
{
    private $intervals;

    public function __construct(Interval ...$intervals)
    {
        $this->intervals = $intervals;
    }

    /**
     * @return Interval[]
     */
    public function getIntervals(): array
    {
        return $this->intervals;
    }
}
