<?php

namespace functional\user;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RegisterUserTest extends WebTestCase
{
    public function testWF11ItRegistersNewUser()
    {
        static::createClient();
        /** @var HttpClientInterface $client */
        $client = static::$container->get(HttpClientInterface::class);
        $response = $client->request(
            'POST',
            '/api/user',
            [
                'base_uri' => 'http://webserver',
                'headers' => ['Content-type' => 'application/json'],
                'json' => ['email' => uniqid().'@example.com', 'password' => 'password1234'],
            ]
        );

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testItReturnsBadRequestWhenPasswordNotSet()
    {
        static::createClient();
        /** @var HttpClientInterface $client */
        $client = static::$container->get(HttpClientInterface::class);
        $response = $client->request(
            'POST',
            '/api/user',
            [
                'base_uri' => 'http://webserver',
                'headers' => ['Content-type' => 'application/json'],
                'json' => ['email' => uniqid().'@example.com'],
            ]
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testItReturnsBadRequestWhenEmailIsInvalid()
    {
        static::createClient();
        /** @var HttpClientInterface $client */
        $client = static::$container->get(HttpClientInterface::class);
        $response = $client->request(
            'POST',
            '/api/user',
            [
                'base_uri' => 'http://webserver',
                'headers' => ['Content-type' => 'application/json'],
                'json' => ['email' => uniqid().'.example.com', 'password' => 'password1234'],
            ]
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
