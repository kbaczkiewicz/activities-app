<?php

namespace functional\interval;

use App\Tests\traits\AuthenticatedRequest;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class EditIntervalTest extends WebTestCase
{
    use AuthenticatedRequest;

    public function testWF24ItEditsNotStartedInterval()
    {
        static::createClient();
        $requestData = [
            'dateStart' => (new DateTime('now + 7 days'))->format('Y-m-d'),
            'dateEnd' => (new DateTime('now + 40 days'))->format('Y-m-d')
        ];

        $response = $this->makeAuthenticatedRequest('GET', '/api/interval', []);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $intervalId = json_decode($response->getContent())->data[0]->id;

        $response = $this->makeAuthenticatedRequest('PATCH', '/api/interval/' . $intervalId, $requestData);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testItReturnsBadRequestWhenTryToSetDateStartFromThePast()
    {
        static::createClient();
        $requestData = [
            'dateStart' => (new DateTime('now - 7 days'))->format('Y-m-d'),
            'dateEnd' => (new DateTime('now + 40 days'))->format('Y-m-d')
        ];

        $response = $this->makeAuthenticatedRequest('GET', '/api/interval', []);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $intervalId = json_decode($response->getContent())->data[0]->id;

        $response = $this->makeAuthenticatedRequest('PATCH', '/api/interval/' . $intervalId, $requestData);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
