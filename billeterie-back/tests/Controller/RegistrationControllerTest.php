<?php
// tests/Controller/RegistrationControllerTest.php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegisterUserSuccessfully(): void
    {
        $client = static::createClient();
        $data = [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'johndoe@example.com',
            'phone' => '1234567890',
            'plainPassword' => 'password123',
            'birthDate' => '2000-01-01',
            'agreeTerms' => true,
        ];

        $client->request('POST', '/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));

        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['message' => 'User registered successfully']),
            $client->getResponse()->getContent()
        );
    }

    public function testRegisterUserWithMissingAgreeTerms(): void
    {
        $client = static::createClient();
        $data = [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'johndoe@example.com',
            'phone' => '1234567890',
            'plainPassword' => 'password123',
            'birthDate' => '2000-01-01',
            // 'agreeTerms' is missing
        ];

        $client->request('POST', '/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['errors' => 'You should agree to our terms.']),
            $client->getResponse()->getContent()
        );
    }

    public function testRegisterUserWithInvalidData(): void
    {
        $client = static::createClient();
        $data = [
            'firstName' => '',
            'lastName' => '',
            'email' => 'not-an-email',
            'phone' => '',
            'plainPassword' => 'short',
            'birthDate' => 'not-a-date',
            'agreeTerms' => true,
        ];

        $client->request('POST', '/register', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('This value is not valid', $client->getResponse()->getContent());
    }
}
