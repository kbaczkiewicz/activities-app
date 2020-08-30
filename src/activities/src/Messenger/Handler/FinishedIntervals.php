<?php


namespace App\Messenger\Handler;


use App\Entity\Interval;

class FinishedIntervals
{
    /** @var Interval[] */
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
