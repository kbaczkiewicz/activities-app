<?php

namespace App\Messenger\Message;

use App\Entity\Activity;
use App\Entity\User;

class SaveActivity
{
    private $user;
    private $activity;

    public function __construct(User $user, Activity $activity)
    {
        $this->user = $user;
        $this->activity = $activity;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getActivity(): Activity
    {
        return $this->activity;
    }
}
