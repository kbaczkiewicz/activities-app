<?php


namespace App\Request\SavedActivity;
use Symfony\Component\Validator\Constraints as Assert;

class CreateSavedActivity
{
    /**
     * @Assert\NotBlank()
     */
    private $name;
    /**
     * @Assert\NotBlank()
     */
    private $typeId;

    public function __construct(?string $name, ?string $typeId)
    {
        $this->name = $name;
        $this->typeId = $typeId;
    }

    public static function fromArray(array $data)
    {
        return new self(
            isset($data['name']) ? $data['name']: null,
            isset($data['typeId']) ? $data['typeId']: null,
        );
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getTypeId(): ?string
    {
        return $this->typeId;
    }
}
