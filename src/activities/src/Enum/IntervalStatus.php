<?php


namespace App\Enum;


class IntervalStatus
{
    const STATUS_NEW = 'new';
    const STATUS_DRAFT = 'draft';
    const STATUS_SAVED = 'saved';
    const STATUS_STARTED = 'started';
    const STATUS_ENDED = 'ended';

    public static function getEditionSafeStatuses()
    {
        return [self::STATUS_NEW, self::STATUS_DRAFT, self::STATUS_SAVED];
    }
}
