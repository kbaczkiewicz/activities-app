<?php

namespace App\Tests\functional\user;

use App\Tests\traits\AuthenticatedRequest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ChangePasswordTest extends WebTestCase
{
    use AuthenticatedRequest;

    public function testWF13ItChangesPasswordForUser()
    {
        static::createClient();
        $response = $this->makeAuthenticatedRequest(
            'PATCH',
            '/api/account',
            ['oldPassword' => 'password1234', 'password' => 'drowssap4321', 'passwordRepeat' => 'drowssap4321'],
            'change.password@example.com'
        );


        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testItReturnsBadRequestWhenPasswordsDoNotMatch()
    {
        static::createClient();
        $response = $this->makeAuthenticatedRequest(
            'PATCH',
            '/api/account',
            ['oldPassword' => 'password1234', 'password' => 'drowssp4321', 'passwordRepeat' => 'drowssap4321']
        );


        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testItReturnsBadRequestWhenOldPasswordDoesNotMatch()
    {
        static::createClient();
        $response = $this->makeAuthenticatedRequest(
            'PATCH',
            '/api/account',
            ['oldPassword' => 'passwor1234', 'password' => 'drowssap4321', 'passwordRepeat' => 'drowssap4321']
        );


        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
