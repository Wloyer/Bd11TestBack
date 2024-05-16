<?php

use App\Controller\ScheduleController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\ScheduleRepository;
use PHPUnit\Framework\TestCase;

class ScheduleControllerTest extends TestCase
{
    public function testGgetAllSchedule()
    {
        // Créer un objet mock du Repository
        $scheduleRepository = $this->createMock(ScheduleRepository::class);
        
        // Préparer le mock pour retourner un ensemble de données fictif (un tableau vide dans ce cas)
        $scheduleRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);
    
        // Créer une instance de ScheduleController en injectant le mock Repository
        $controller = new ScheduleController($scheduleRepository);
    
        // Appeler la méthode à tester
        $response = $controller->index($scheduleRepository);
    
        // Vérifier que la réponse est une instance de JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);
    
        // Vérifier que le contenu de la réponse est conforme aux attentes
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('schedules', $responseData);
        $this->assertEmpty($responseData['schedules']);
    }

    // public function testNewSchedule()
    // {

    // }
    // public function testShowSchedule()
    // {

    // }

    // public function testEditSchedule()
    // {

    // }

    // public function testDeleteSchedule()
    // {

    // }
}