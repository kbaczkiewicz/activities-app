<?php

namespace App\Tests\functional\interval;

use App\Entity\Activity;
use App\Entity\ActivityType;
use App\Entity\Interval;
use App\Repository\ActivityRepository;
use App\Repository\ActivityTypeRepository;
use App\Repository\IntervalRepository;
use App\Tests\traits\AuthenticatedRequest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AssignActivityToIntervalTest extends WebTestCase
{
    use AuthenticatedRequest;

    public function testWF23CreateAndAssignActivitiesToInterval()
    {
        static::createClient();
        $requestData = [
            'name' => 'Test interval '.md5(uniqid()),
            'dateStart' => (new \DateTime('now'))->format('Y-m-d'),
            'dateEnd' => (new \DateTime('now + 21 days'))->format('Y-m-d'),
        ];
        $response = $this->makeAuthenticatedRequest('POST', '/api/interval', $requestData);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        /** @var Interval $interval */
        $interval = static::$container->get(IntervalRepository::class)->findOneBy(['name' => $requestData['name']]);
        /** @var ActivityType $activityType */
        $activityType = static::$container->get(ActivityTypeRepository::class)->findOneBy(['name' => 'Codzienna']);
        $activityRequestData = [
            'name' => 'Test activity '.md5(uniqid()),
            'typeId' => $activityType->getId(),
        ];

        $response = $this->makeAuthenticatedRequest('POST', '/api/activity', $activityRequestData);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        /** @var Activity $activity */
        $activity = static::$container->get(ActivityRepository::class)
            ->findOneBy(['name' => $activityRequestData['name']]);

        $response = $this->makeAuthenticatedRequest(
            'PATCH',
            sprintf('/api/interval/%s/activities', $interval->getId()),
            ['ids' => [$activity->getId()]]
        );

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

    }
}
