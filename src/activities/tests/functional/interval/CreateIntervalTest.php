<?php

namespace App\Tests\functional\interval;

use App\Tests\traits\AuthenticatedRequest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CreateIntervalTest extends WebTestCase
{
    use AuthenticatedRequest;

    public function testWF21ItCreatesInterval()
    {
        static::createClient();
        $requestData = [
            'name' => 'Test interval',
            'dateStart' => (new \DateTime('now'))->format('Y-m-d'),
            'dateEnd' => (new \DateTime('now + 21 days'))->format('Y-m-d')
        ];

        $response = $this->makeAuthenticatedRequest('POST', '/api/interval', $requestData);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testItReturnsBadRequestWhenDateIsFromThePast()
    {
        static::createClient();
        $requestData = [
            'name' => 'Test interval',
            'dateStart' => (new \DateTime('now -1 day'))->format('Y-m-d'),
            'dateEnd' => (new \DateTime('now + 21 days'))->format('Y-m-d')
        ];

        $response = $this->makeAuthenticatedRequest('POST', '/api/interval', $requestData);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testItReturnsBadRequestWhenNameIsEmpty()
    {
        static::createClient();
        $requestData = [
            'name' => '',
            'dateStart' => (new \DateTime('now'))->format('Y-m-d'),
            'dateEnd' => (new \DateTime('now + 21 days'))->format('Y-m-d')
        ];

        $response = $this->makeAuthenticatedRequest('POST', '/api/interval', $requestData);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
