<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class IntervalStats
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string")
     */
    private $id;
    /**
     * @ORM\OneToOne(targetEntity="Interval", inversedBy="stats")
     * @ORM\JoinColumn(name="interval_id", referencedColumnName="id")
     */
    private $interval;
    /**
     * @ORM\ManyToMany(targetEntity="Activity")
     * @ORM\JoinTable(name="stats_completed_activities",
     *     joinColumns={@ORM\JoinColumn(name="activity_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="stats_id", referencedColumnName="id")}
     *     )
     */
    private $completedActivities;
    /**
     * @ORM\ManyToMany(targetEntity="Activity")
     * @ORM\JoinTable(name="stats_failed_activities",
     *     joinColumns={@ORM\JoinColumn(name="activity_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="stats_id", referencedColumnName="id")}
     *     )
     */
    private $failedActivities;

    public function __construct()
    {
        $this->completedActivities = new ArrayCollection();
        $this->failedActivities = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getInterval(): ?Interval
    {
        return $this->interval;
    }

    public function setInterval(Interval $interval): void
    {
        $this->interval = $interval;
    }

    public function getCompletedActivities()
    {
        return $this->completedActivities;
    }

    public function setCompletedActivities(Activity ...$completedActivities): void
    {
        foreach ($completedActivities as $completedActivity) {
            $this->completedActivities->add($completedActivity);
        }
    }

    public function setFailedActivities(Activity ...$failedActivities)
    {
        foreach ($failedActivities as $failedActivity) {
            $this->failedActivities->add($failedActivity);
        }
    }

    public function getFailedActivities($failedActivities): void
    {
        $this->failedActivities = $failedActivities;
    }
}
