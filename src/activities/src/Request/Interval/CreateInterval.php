<?php


namespace App\Request\Interval;

use App\Validation\Constraints\DateNotFromPast;
use Symfony\Component\Validator\Constraints as Assert;

class CreateInterval
{
    /**
     * @Assert\NotBlank(message="Name should not be blank")
     */
    private $name;
    /**
     * @Assert\NotBlank(message="Date start should not be blank")
     * @DateNotFromPast()
     */
    private $dateStart;
    /**
     * @Assert\NotBlank(message="Date end should not be blank")
     * @DateNotFromPast()
     */
    private $dateEnd;

    public function __construct(?string $name, ?\DateTime $dateStart, ?\DateTime $dateEnd)
    {
        $this->name = $name;
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
    }

    public static function fromArray(array $data)
    {
        return new self(
            isset($data['name']) ? $data['name'] : null,
            isset($data['dateStart']) ? \DateTime::createFromFormat('Y-m-d', $data['dateStart']) : null,
            isset($data['dateEnd']) ? \DateTime::createFromFormat('Y-m-d', $data['dateEnd']) : null
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDateStart(): \DateTime
    {
        return $this->dateStart;
    }

    public function getDateEnd(): \DateTime
    {
        return $this->dateEnd;
    }
}
