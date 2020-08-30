<?php


namespace App\Response;

use App\Entity\Activity;
use App\Entity\Interval as IntervalEntity;
use App\Value\Identificator\ActivityId;
use App\Value\Identificator\IntervalId;

class Interval implements \JsonSerializable
{
    private $id;
    private $name;
    private $status;
    private $activityIds;
    private $dateStart;
    private $dateEnd;

    public function __construct(
        IntervalId $id,
        string $name,
        \DateTime $dateStart,
        \DateTime $dateEnd,
        string $status,
        ActivityId ...$activityIds
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
        $this->status = $status;
        $this->activityIds = $activityIds;
    }

    public static function fromModel(IntervalEntity $model)
    {
        return new self(
            new IntervalId($model->getId()),
            $model->getName(),
            $model->getDateStart(),
            $model->getDateEnd(),
            $model->getStatus(),
            ...array_map(
                function (Activity $activity) {
                    return new ActivityId($activity->getId());
                },
                $model->getActivities()->toArray()
            )
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id->getId(),
            'name' => $this->name,
            'status' => $this->status,
            'dateStart' => $this->dateStart ? $this->dateStart->format('Y-m-d') : null,
            'dateEnd' => $this->dateEnd ? $this->dateEnd->format('Y-m-d') : null,
            'activityIds' => array_map(
                function (ActivityId $id) {
                    return $id->getId();
                },
                $this->activityIds
            ),
        ];
    }
}
