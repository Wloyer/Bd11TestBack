<?php
// tests/Controller/RegistrationControllerTest.php
// tests/Controller/RegistrationControllerTest.php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testSuccessfulRegistration()
    {
        $client = static::createClient();

        // Ensure the route exists
        $crawler = $client->request('POST', '/api/register');
        $this->assertNotEquals(404, $client->getResponse()->getStatusCode(), 'Route "/api/register" does not exist.');

        // Simulate a POST request to the registration route
        $client->request(
            'POST',
            '/api/register',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'testuser@example.com',
                "firstname" => "John",
                "lastname" => "Doe",
                "birthdate" => "1990-01-01",
                "phone" => "1234567890",
                'plainPassword' => 'testpassword',
                'agreeTerms' => true
            ])
        );

        // Assert the response is a success and contains the expected message
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['message' => 'User registered successfully']),
            $client->getResponse()->getContent()
        );
    }

    public function testRegistrationFailsWithoutAgreeingToTerms()
    {
        $client = static::createClient();

        // Ensure the route exists
        $crawler = $client->request('POST', '/api/register');
        $this->assertNotEquals(404, $client->getResponse()->getStatusCode(), 'Route "/api/register" does not exist.');

        // Simulate a POST request to the registration route without agreeing to terms
        $client->request(
            'POST',
            '/api/register',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'testuser@example.com',
                "firstname" => "John",
                "lastname" => "Doe",
                "birthdate" => "1990-01-01",
                "phone" => "1234567890",
                'plainPassword' => 'testpassword',
                'agreeTerms' => false
            ])
        );

        // Assert the response is a bad request and contains the expected error
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['errors' => 'You should agree to our terms.']),
            $client->getResponse()->getContent()
        );
    }
}
