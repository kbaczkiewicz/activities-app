<?php


namespace App\Request\Activity;


use App\Value\Identificator\ActivityTypeId;
use Symfony\Component\Validator\Constraints as Assert;

class CreateActivity
{
    /**
     * @Assert\NotBlank()
     */
    private $name;
    /**
     * @Assert\NotBlank()
     */
    private $typeId;

    public function __construct(?string $name = null, ?ActivityTypeId $typeId = null)
    {
        $this->name = $name;
        $this->typeId = $typeId;
    }

    public static function fromArray(array $data)
    {
        return new self(
            $data['name'],
            new ActivityTypeId($data['typeId'])
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
