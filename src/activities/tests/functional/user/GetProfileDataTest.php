<?php

namespace App\Tests\functional\user;

use App\Tests\traits\AuthenticatedRequest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GetProfileDataTest extends WebTestCase
{
    use AuthenticatedRequest;

    public function testWF14ItShowsUserData()
    {
        static::createClient();
        $response = $this->makeAuthenticatedRequest('GET', 'api/account', []);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertStringContainsString('"email":"kamil.baczkiewicz@example.com', $response->getContent());
    }
}
