<?php


namespace App\Response;


use App\Entity\Activity as ActivityEntity;
use App\Value\Identificator\ActivityId;
use App\Value\Identificator\ActivityTypeId;
use App\Value\Identificator\IntervalId;

class Activity implements \JsonSerializable
{
    private $id;
    private $name;
    private $dateStart;
    private $typeId;
    private $intervalId;
    private $occurrences;

    public function __construct(
        ActivityId $id,
        string $name,
        ActivityTypeId $typeId,
        ?IntervalId $intervalId = null,
        ?\DateTime $dateStart = null,
        array $occurrences = []
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->dateStart = $dateStart;
        $this->typeId = $typeId;
        $this->intervalId = $intervalId;
        $this->occurrences = $occurrences;
    }

    public static function fromModel(ActivityEntity $activity)
    {
        return new self(
            new ActivityId($activity->getId()),
            $activity->getName(),
            new ActivityTypeId($activity->getType()->getId()),
            $activity->getInterval() ? new IntervalId($activity->getInterval()->getId()) : null,
            $activity->getDateStart() ?? null,
            $activity->getOccurrencesOfActivity()
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id->getId(),
            'name' => $this->name,
            'dateStart' => $this->dateStart ? $this->dateStart->format('Y-m-d') : null,
            'activityTypeId' => $this->typeId->getId(),
            'intervalId' => $this->intervalId ? $this->intervalId->getId() : null,
            'occurrences' => $this->occurrences
        ];
    }
}
