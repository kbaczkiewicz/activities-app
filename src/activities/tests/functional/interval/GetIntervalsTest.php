<?php

namespace App\Tests\functional\interval;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\traits\AuthenticatedRequest;
use Symfony\Component\HttpFoundation\Response;

class GetIntervalsTest extends WebTestCase
{
    use AuthenticatedRequest;

    public function testWF25ItListsUserIntervals()
    {
        static::createClient();
        $response = $this->makeAuthenticatedRequest('GET', '/api/interval', []);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $responseBody = json_decode($response->getContent());
        $this->assertGreaterThanOrEqual(8, $responseBody->data); // at least 8 intervals from fixtures
    }

    public function testWF41ItListsUserIntervalsByFilters()
    {
        static::createClient();
        $response = $this->makeAuthenticatedRequest('GET', '/api/interval?status[]=ended', []);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $responseBody = json_decode($response->getContent());
        $this->assertCount(3, $responseBody->data);
    }
}
