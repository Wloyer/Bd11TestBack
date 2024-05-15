<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\PdfGenerator;

#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/', name: 'app_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    //fonctionnalité permettant de retourner les informations de l'event nécessaires pour l'utilisateur visiteur
    #[Route('/event-informations', name: 'app_event_indexation', methods: ['GET'])]
    public function indexatiton(EventRepository $eventInformationRepository): Response
    {
        // return $this->render('event/index.html.twig', [
        //     'events' => $eventRepository->findAll(),
        // ]);
        $events = $eventInformationRepository->findAll();
        // return new JsonResponse($events);
            $size = sizeof($events);
            $results = array();
            for($i = 0 ; $i < $size; $i++){
                $temp = array("name"=> $events[$i]->getName(), "price" => $events[$i]->getPrice(), "date" =>$events[$i]->getEventDate(), "image" => $events[$i]->getImageEvent() );
                array_push($results, $temp );

            }
        $response = new Response(json_encode( array( "resultat" => $results) ));
        $response->headers->set('Content-Type', 'application/json');
        return $response; 
    }


    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
    }

    public function generatePdfAction(Request $request, PdfGenerator $pdfGenerator, EventRepository $eventRepository)
    {

        // Récupérer l'identifiant de l'événement à partir de la requête ou d'autres sources
        $eventId = $request->query->get('eventId');

        // Récupérer l'événement à partir de la base de données
        $event = $eventRepository->find($eventId);

        // Vérifier si l'événement existe
        if (!$event) {
            throw $this->createNotFoundException('Event not found');
        }
        // Obtenir les données nécessaires à partir de la requête ou d'autres sources
        $eventName = $event->getName();
        $reservationDate = $event->getBookingDate()->format('Y-m-d');
        $eventDate = $event->getEventDate()->format('Y-m-d');
        $numberOfAttendees = 5;
        $reservationUuid = "faut je cherche0";

        // Générer le PDF en utilisant le service PdfGenerator
        $pdfContent = $pdfGenerator->generatePdf($eventName, $eventDate, $reservationDate, $numberOfAttendees, $reservationUuid);

        // Renvoyer le PDF en réponse à la requête HTTP
        return new Response($pdfContent, 200, array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="reservation.pdf"'
        ));
    }
}
