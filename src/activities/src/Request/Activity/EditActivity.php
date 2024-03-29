<?php

namespace App\Request\Activity;

use App\Value\Identificator\ActivityTypeId;
use App\Validation\Constraints\AtLeastOneNotBlank;

/**
 * @AtLeastOneNotBlank()
 */
class EditActivity
{
    private $name;
    private $typeId;

    public function __construct(?string $name, ?ActivityTypeId $typeId)
    {
        $this->name = $name;
        $this->typeId = $typeId;
    }

    public static function fromArray(array $data): EditActivity
    {
        if (0 === count($data)) {
            return new EmptyEditActivity();
        }

        return new self(
            isset($data['name']) ? $data['name'] : null,
            isset($data['typeId']) ? new ActivityTypeId($data['typeId']) : null
        );
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getTypeId(): ?ActivityTypeId
    {
        return $this->typeId;
    }
}
