<?php


namespace App\Request\Interval;


use App\Validation\Constraints\DateNotFromPast;

trait IntervalRequestTrait
{
    /**
     * @Assert\NotBlank(message="Name should not be blank")
     */
    private $name;
    /**
     * @DateNotFromPast()
     */
    private $dateStart;
    /**
     * @DateNotFromPast()
     */
    private $dateEnd;

    public function __construct(string $name, \DateTime $dateStart, \DateTime $dateEnd)
    {
        $this->name = $name;
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
    }

    public static function fromArray(array $data)
    {
        return new self(
            $data['name'],
            \DateTime::createFromFormat('Y-m-d H:i:s', $data['dateStart']),
            \DateTime::createFromFormat('Y-m-d H:i:s', $data['dateEnd'])
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
