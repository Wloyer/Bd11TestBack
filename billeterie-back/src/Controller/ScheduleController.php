<?php

namespace App\Controller;

use App\Entity\Schedule;
use App\Form\ScheduleType;
use App\Repository\ScheduleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/schedule')]
class ScheduleController extends AbstractController
{
    #[Route('/', name: 'app_schedule_index', methods: ['GET'])]
    public function index(ScheduleRepository $scheduleRepository): JsonResponse
    {
        // return $this->render('schedule/index.html.twig', [
        //     'schedules' => $scheduleRepository->findAll(),
        // ]);

        $schedules = $scheduleRepository->findAll();
        return $this->json(['schedules' => $schedules]);
    }

    #[Route('/new', name: 'app_schedule_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $schedule = new Schedule();
        $form = $this->createForm(ScheduleType::class, $schedule);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($schedule);
            $entityManager->flush();
    
            
            return $this->json(['id' => $schedule->getId()], Response::HTTP_CREATED);
        }
    
        
        return $this->json(['errors' => (string) $form->getErrors(true)], Response::HTTP_BAD_REQUEST);
    } 

    #[Route('/{id}', name: 'app_schedule_show', methods: ['GET'])]
    public function show(Schedule $schedule): Response
    {
        return $this->json($schedule);
    }

    #[Route('/{id}/edit', name: 'app_schedule_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Schedule $schedule, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ScheduleType::class, $schedule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_schedule_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('schedule/edit.html.twig', [
            'schedule' => $schedule,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_schedule_delete', methods: ['POST'])]
    public function delete(Request $request, Schedule $schedule, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$schedule->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($schedule);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_schedule_index', [], Response::HTTP_SEE_OTHER);
    }
}
