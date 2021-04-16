<?php

namespace functional\stats;

use App\Tests\traits\AuthenticatedRequest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ShowIntervalStatsTest extends WebTestCase
{
    use AuthenticatedRequest;

    public function testWF41ItShowsStatsForFinishedInterval()
    {
        static::createClient();
        $response = $this->makeAuthenticatedRequest('GET', '/api/interval?status[]=ended', []);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $intervalId = json_decode($response->getContent())->data[0]->id;

        $response = $this->makeAuthenticatedRequest('GET', '/api/intervalStats/' . $intervalId, []);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertStringContainsString('completedAmount', $response->getContent());
        $this->assertStringContainsString('failedAmount', $response->getContent());
        $this->assertStringContainsString('completedPercent', $response->getContent());
    }

    public function testItReturnsUnauthorizedWhenTryToSeeStatsWhileNotLoggedIn()
    {
        static::createClient();
        $client = static::$container->get(HttpClientInterface::class);
        $response = $this->makeAuthenticatedRequest('GET', '/api/interval?status[]=ended', []);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $intervalId = json_decode($response->getContent())->data[0]->id;

        $response = $client->request(
            'GET',
            '/api/intervalStats/' . $intervalId,
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
