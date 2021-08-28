<?php


namespace App\Entity;


use App\Enum\ActivityStatus;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActivityRepository")
 * @ORM\Table(name="activity")
 */
class Activity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateStart;

    /**
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="Interval", inversedBy="activities")
     * @ORM\JoinColumn(name="interval_id", referencedColumnName="id")
     */
    private $interval;

    /**
     * @ORM\ManyToOne(targetEntity="ActivityType")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDateStart(): ?\DateTime
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTime $dateStart): void
    {
        $this->dateStart = $dateStart;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getInterval(): ?Interval
    {
        return $this->interval;
    }

    public function setInterval(Interval $interval): void
    {
        $this->interval = $interval;
    }

    public function getType(): ?ActivityType
    {
        return $this->type;
    }

    public function setType(ActivityType $type): void
    {
        $this->type = $type;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): void
    {
        $this->user = $user;
    }

    public function getDateEnd(): ?\DateTime
    {
        /** @var \DateTime $dateStart */
        $dateStart = $this->dateStart;
        if (false === $this->dateStart) {
            return null;
        }

        return $dateStart
            ->add(new \DateInterval(sprintf('P%dD', $this->getType()->getDaysSpan())));
    }

    public function createNewIterationOfActivity(): self
    {
        $dateStart = $this->getDateEnd();
        if (null === $dateStart) {
            throw new \LogicException('Cannot start a new activity as previous does not have an ending date');
        }

        $newActivity = new Activity();
        $newActivity->setName($this->name);
        $newActivity->setDateStart($dateStart);
        $newActivity->setUser($this->getUser());
        $newActivity->setStatus(ActivityStatus::STATUS_CREATED);
        $newActivity->setType($this->getType());
        $newActivity->setInterval($this->getInterval());

        return $newActivity;
    }

    public function getOccurrencesOfActivity(): array
    {
        $occurrences = [];
        $activity = $this;
        while ($activity->getDateStart() <= $this->getInterval()->getDateEnd()) {
            $occurrences[] = $activity->getDateStart()->format('d.m.Y');
            $activity = $this->createNewIterationOfActivity();
        }

        return $occurrences;
    }

    public function shouldStart(): bool
    {
        $today = new \DateTime('today 00:00:00');
        $dateStart = $this->getDateStart();
        $dateStart->setTime(0, 0, 0);

        return $today === $dateStart;
    }
}
