<?php

namespace App\Request\Activity;

class EmptyEditActivity extends EditActivity
{

    public function __construct()
    {
        parent::__construct(null, null);
    }
}
