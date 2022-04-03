<?php

namespace functional\activity;

use App\Enum\ActivityStatus;
use App\Repository\ActivityRepository;
use App\Tests\traits\AuthenticatedRequest;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MarkActivityAsFinishedTest extends WebTestCase
{
    use AuthenticatedRequest;

    public function testW32ItEditsNonPendingActivity()
    {
        static::createClient();

        $response = $this->makeAuthenticatedRequest('GET', '/api/interval', []);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $activityId = json_decode($response->getContent())->data[0]->activityIds[0];

        $response = $this->makeAuthenticatedRequest('PATCH', '/api/activity/'.$activityId, []);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
        $this->assertNotEquals(
            ActivityStatus::STATUS_COMPLETED,
            self::$container->get(ActivityRepository::class)->find($activityId)->getStatus()
        );
    }

    public function testItReturnsBadRequestWhenNameUnauthorized()
    {
        static::createClient();
        $client = self::$container->get(HttpClientInterface::class);

        $response = $this->makeAuthenticatedRequest('GET', '/api/interval', []);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $activityId = json_decode($response->getContent())->data[0]->activityIds[0];

        $response = $client->request(
            'PATCH',
            '/api/activity/'.$activityId,
            [
                'base_uri' => 'http://webserver',
                'headers' => [
                    'Content-type' => 'application/json',
                ],
            ]
        );

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }
}
