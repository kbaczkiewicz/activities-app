<?php


namespace App\Request\Activity;


use App\Validation\Constraints\DateNotFromPast;
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
    /**
     * @Assert\NotBlank()
     * @DateNotFromPast()
     */
    private $dateStart;

    public function __construct(?string $name = null, ?ActivityTypeId $typeId = null, ?\DateTime $dateStart = null)
    {
        $this->name = $name;
        $this->typeId = $typeId;
        $this->dateStart = $dateStart;
    }

    public static function fromArray(array $data)
    {
        $date = isset($data['dateStart']) ? \DateTime::createFromFormat('Y-m-d', $data['dateStart']) : null;
        if ($date) {
            $date->setTime(0, 0, 0);
        }

        return new self(
            isset($data['name']) ? $data['name'] : null,
            isset($data['typeId']) ? new ActivityTypeId($data['typeId']) : null,
            $date
        );
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDateStart(): ?\DateTime
    {
        return $this->dateStart;
    }

    public function getTypeId(): ?ActivityTypeId
    {
        return $this->typeId;
    }
}
