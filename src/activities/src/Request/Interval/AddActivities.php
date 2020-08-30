<?php


namespace App\Request\Interval;


use App\Value\Identificator\ActivityId;
use App\Value\Identificator\IntervalId;
use Symfony\Component\Validator\Constraints as Assert;

class AddActivities
{
    /**
     * @var ActivityId[]
     * @Assert\NotBlank()
     */
    private $activitiesIds;

    public function __construct(ActivityId ...$activitiesIds)
    {
        $this->activitiesIds = $activitiesIds;
    }

    public static function fromArray(array $content): self
    {
        return new self(
            ...array_map(
            function (string $id) {
                return new ActivityId($id);
            },
            $content['ids']
        ));
    }

    /**
     * @return ActivityId[]
     */
    public function getActivitiesIds(): array
    {
        return $this->activitiesIds;
    }
}
