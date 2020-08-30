<?php


namespace App\Response;


use App\Entity\SavedActivity as SavedActivityEntity;
use App\Value\Identificator\ActivityTypeId;
use App\Value\Identificator\SavedActivityId;

class SavedActivity implements \JsonSerializable
{
    private $id;
    private $name;
    private $typeId;

    public function __construct(SavedActivityId $id, string $name, ActivityTypeId $typeId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->typeId = $typeId;
    }

    public static function fromModel(SavedActivityEntity $model)
    {
        return new self(new SavedActivityId($model->getId()), $model->getName(), new ActivityTypeId($model->getType()->getId()));
    }

    public function jsonSerialize(): array
    {
        return [
          'id' => $this->id->getId(),
          'name' => $this->name,
          'typeId' => $this->typeId->getId()
        ];
    }
}
