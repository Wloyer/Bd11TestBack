<?php
// tests/Controller/LoginControllerTest.php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginControllerTest extends WebTestCase
{
    private $entityManager;
    private $passwordHasher;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer(); // Adjusted for Symfony 5.3+

        // Fetch the EntityManager and PasswordHasher services
        $this->entityManager = $container->get('doctrine')->getManager();
        $this->passwordHasher = $container->get(UserPasswordHasherInterface::class);

        // Create a test user
        $user = new User();
        $user->setEmail('john.doe@example.com');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
        $user->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Remove the test user
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'john.doe@example.com']);
        if ($user) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        }

        $this->entityManager->close();
        $this->entityManager = null; // Avoid memory leaks
    }

    public function testSuccessfulLogin()
    {
        $client = static::createClient();

        // Simulate a POST request to the login route with valid credentials
        $client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'john.doe@example.com',
                'password' => 'password123'
            ])
        );

        // Assert the response is a success and contains the expected message
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['message' => 'Logged in successfully']),
            $client->getResponse()->getContent()
        );
    }

    public function testLoginFailsWithInvalidCredentials()
    {
        $client = static::createClient();

        // Simulate a POST request to the login route with invalid credentials
        $client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'invalid@example.com',
                'password' => 'invalidpassword'
            ])
        );

        // Assert the response is an unauthorized error
        $this->assertEquals(401, $client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['error' => 'Invalid credentials.']),
            $client->getResponse()->getContent()
        );
    }

}
