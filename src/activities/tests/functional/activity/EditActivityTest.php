<?php

namespace functional\activity;

use App\Tests\traits\AuthenticatedRequest;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class EditActivityTest extends WebTestCase
{
    use AuthenticatedRequest;

    public function testWF24ItEditsNonPendingActivity()
    {
        static::createClient();
        $requestData = [
            'name' => 'Zmieniona nazwa',
        ];

        $response = $this->makeAuthenticatedRequest('GET', '/api/interval', []);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $activityId = json_decode($response->getContent())->data[0]->activityIds[0];

        $response = $this->makeAuthenticatedRequest('PATCH', '/api/activity/'.$activityId, $requestData);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testItReturnsBadRequestWhenNameIsEmpty()
    {
        static::createClient();
        $requestData = [
            'name' => '',
        ];

        $response = $this->makeAuthenticatedRequest('GET', '/api/interval', []);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $activityId = json_decode($response->getContent())->data[0]->activityIds[0];

        $response = $this->makeAuthenticatedRequest('PATCH', '/api/activity/'.$activityId, $requestData);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
