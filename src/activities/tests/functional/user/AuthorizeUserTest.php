<?php

namespace functional\user;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AuthorizeUserTest extends WebTestCase
{
    public function testWF12ItAuthorizesUser()
    {
        static::createClient();
        /** @var HttpClientInterface $client */
        $client = static::$container->get(HttpClientInterface::class);
        $response = $client->request(
            'POST',
            '/api/login_check',
            [
                'base_uri' => 'http://webserver',
                'json' => ['username' => 'kamil.baczkiewicz@example.com', 'password' => 'password1234'],
            ]
        );

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertStringContainsString('token', $response->getContent());
    }

    public function testItReturnsUnauthorizedWhenBadCredentialsProvided()
    {
        static::createClient();
        /** @var HttpClientInterface $client */
        $client = static::$container->get(HttpClientInterface::class);
        $response = $client->request(
            'POST',
            '/api/login_check',
            [
                'base_uri' => 'http://webserver',
                'json' => ['username' => md5(uniqid()) . '@example.com', 'password' => 'password1234'],
            ]
        );

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }
}
