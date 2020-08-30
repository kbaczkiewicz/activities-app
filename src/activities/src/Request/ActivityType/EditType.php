<?php


namespace App\Request\ActivityType;

use Symfony\Component\Validator\Constraints as Assert;

class EditType
{
    private $name;
    private $daysSpan;

    public function __construct(?string $name, ?int $daysSpan)
    {
        $this->name = $name;
        $this->daysSpan = $daysSpan;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['name']) ? $data['name'] : null,
            isset($data['daysSpan']) ? $data['daysSpan'] : null,
            );
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDaysSpan(): ?int
    {
        return $this->daysSpan;
    }
}
