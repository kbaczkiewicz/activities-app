<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\ActivityType;
use App\Entity\Interval;
use App\Entity\SavedActivity;
use App\Entity\User;
use App\Enum\ActivityStatus;
use App\Enum\IntervalStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    const INTERVAL_ID = 'd8c130a9-fe1b-4274-a2b7-ed77d3bed0ae';
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = $this->createUser();
        $interval = $this->createInterval($user);
        $activityTypes = $this->createActivityTypes($user);
        $activities = $this->createActivities($interval, $user, $activityTypes);
        $savedActivity = $this->createSavedActivity($user, $activityTypes['daily']);
        $manager->persist($user);
        $manager->persist($interval);
        foreach ($activityTypes as $type) {
            $manager->persist($type);
        }

        $manager->persist($savedActivity);

        foreach ($activities as $activity) {
            $manager->persist($activity);
        }

        $manager->flush();
    }

    private function createUser(): User
    {
        $user = new User();
        $user->setPassword($this->passwordEncoder->encodePassword($user, '1234qwer'));
        $user->setEmail('a@b.cd');
        $user->setRoles(['ROLE_USER']);

        return $user;
    }

    private function createInterval(User $user): Interval
    {
        $interval = new Interval();
        $interval->setName('Interval');
        $interval->setStatus(IntervalStatus::STATUS_NEW);
        $interval->setUser($user);
        $interval->setDateStart(new \DateTime());
        $interval->setDateEnd(new \DateTime('now + 1 month'));
        $interval->setId(self::INTERVAL_ID);

        return $interval;
    }

    /**
     * @return ActivityType[]
     */
    private function createActivityTypes(User $user): array
    {
        $types = [];
        $types['daily'] = new ActivityType();
        $types['daily']->setName('Daily');
        $types['daily']->setUser($user);
        $types['daily']->setDaysSpan(1);
        $types['daily']->setId(Uuid::uuid4());
        $types['weekly'] = new ActivityType();
        $types['weekly']->setName('Weekly');
        $types['weekly']->setUser($user);
        $types['weekly']->setDaysSpan(7);
        $types['weekly']->setId(Uuid::uuid4());

        return $types;
    }

    /**
     * @return Activity[]
     */
    private function createActivities(Interval $interval, User $user, array $activityTypes): array
    {
        $activities = [];
        $activities[0] = new Activity();
        $activities[0]->setId(Uuid::uuid4());
        $activities[0]->setUser($user);
        $activities[0]->setStatus(ActivityStatus::STATUS_CREATED);
        $activities[0]->setDateStart($interval->getDateStart());
        $activities[0]->setType($activityTypes['daily']);
        $activities[0]->setName('Daily activity');
        $activities[0]->setInterval($interval);
        $activities[1] = new Activity();
        $activities[1]->setId(Uuid::uuid4());
        $activities[1]->setUser($user);
        $activities[1]->setStatus(ActivityStatus::STATUS_CREATED);
        $activities[1]->setDateStart($interval->getDateStart());
        $activities[1]->setType($activityTypes['weekly']);
        $activities[1]->setName('Weekly activity');
        $activities[1]->setInterval($interval);
        $interval->addActivities(...$activities);

        return $activities;
    }

    private function createSavedActivity(User $user, ActivityType $type)
    {
        $activity = new SavedActivity();
        $activity->setName('Saved');
        $activity->setType($type);
        $activity->setUser($user);

        return $activity;
    }
}
