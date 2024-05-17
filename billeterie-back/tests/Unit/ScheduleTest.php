<?php

namespace App\Tests\Unit;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ScheduleTest extends WebTestCase
{
    // on réalise la fonction testIndex pour tester la fonction index du ScheduleController
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/schedule'); 

        $this->assertResponseIsSuccessful();
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $client->getResponse());

    }

    // on réalise la fonction testNew pour tester la fonction new du ScheduleController
    public function testNew(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/schedule/new', [], [], [], json_encode([]));

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    //fonction testShow pour tester la fonction show du ScheduleController
    public function testShow(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/schedule/1');

        $this->assertResponseIsSuccessful();
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $client->getResponse());
    }

    //fonction testEdit pour tester la fonction edit du ScheduleController
    public function testEdit(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/schedule/1/edit');

        $this->assertResponseIsSuccessful();
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $client->getResponse());
    }

    //fonction testDelete pour tester la fonction delete du ScheduleController
    public function testDelete(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/schedule/1/delete');

        $this->assertEquals(302, $client->getResponse()->getStatusCode()); 
    }

    
}
