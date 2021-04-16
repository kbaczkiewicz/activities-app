<?php

namespace App\Messenger\Message;

use App\Entity\Activity;

class NewActivityIteration
{
    private $activity;

    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    public function getActivity(): Activity
    {
        return $this->activity;
    }
}
