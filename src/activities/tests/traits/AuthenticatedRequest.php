<?php

namespace App\Tests\traits;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

trait AuthenticatedRequest
{
    public function makeAuthenticatedRequest(
        string $method,
        string $url,
        array $params,
        string $email = 'kamil.baczkiewicz@example.com'
    ): ResponseInterface {
        /** @var HttpClientInterface $client */
        $client = static::$container->get(HttpClientInterface::class);
        $response = $client->request(
            'POST',
            '/api/login_check',
            [
                'base_uri' => 'http://webserver',
                'json' => ['username' => $email, 'password' => 'password1234'],
            ]
        );
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        return $client->request(
            $method,
            $url,
            [
                'base_uri' => 'http://webserver',
                'headers' => [
                    'Authorization' => 'Bearer '.json_decode($response->getContent())->token,
                    'Content-type' => 'application/json',
                ],
                'json' => $params,
            ]
        );
    }
}
