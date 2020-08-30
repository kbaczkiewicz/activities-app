<?php


namespace App\Response;


use App\Value\Identificator\ActivityTypeId;

class ActivityType implements \JsonSerializable
{
    private $id;
    private $name;
    private $daysSpan;

    public function __construct(ActivityTypeId $id, string $name, int $daysSpan)
    {
        $this->id = $id;
        $this->name = $name;
        $this->daysSpan = $daysSpan;
    }

    public static function fromModel(\App\Entity\ActivityType $type): self
    {
        return new self(
            new ActivityTypeId($type->getId()),
            $type->getName(),
            $type->getDaysSpan()
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id->getId(),
            'name' => $this->name,
            'daysSpan' => $this->daysSpan,
        ];
    }
}
