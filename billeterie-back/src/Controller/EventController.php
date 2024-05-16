<?php
namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PdfGenerator;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[Route('/event')]
class EventController extends AbstractController
{
    // Inject the AuthorizationCheckerInterface to check roles
    public function __construct(private AuthorizationCheckerInterface $authorizationChecker)
    {
    }

    #[Route('/', name: 'app_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): JsonResponse
    {
        $events = $eventRepository->findAll();
        return $this->json(['events' => $events]);
    }

    #[Route('/event-informations', name: 'app_event_indexation', methods: ['GET'])]
    public function indexatiton(EventRepository $eventInformationRepository): JsonResponse
    {
        // Check if the user has ROLE_ADMIN
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Only admins can create events.');
        }
        $events = $eventInformationRepository->findAll();
        $results = array_map(function ($event) {
            return [
                'name' => $event->getName(),
                'price' => $event->getPrice(),
                'date' => $event->getEventDate()->format('Y-m-d H:i:s'),
                'image' => $event->getImageEvent(),
            ];
        }, $events);
        return new JsonResponse(['resultat' => $results]);
    }

    #[Route('/new', name: 'app_event_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($event);
            $entityManager->flush();

            return new JsonResponse(['status' => 'Event created'], Response::HTTP_CREATED);
        }

        return new JsonResponse(['status' => 'Invalid data', 'errors' => (string) $form->getErrors(true, false)], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): JsonResponse
    {
        return $this->json($event);
    }

    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(EventType::class, $event);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return new JsonResponse(['status' => 'Event updated']);
        }

        return new JsonResponse(['status' => 'Invalid data', 'errors' => (string) $form->getErrors(true, false)], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/{id}', name: 'app_event_delete', methods: ['DELETE'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): JsonResponse
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();

            return new JsonResponse(['status' => 'Event deleted']);
        }

        return new JsonResponse(['status' => 'Invalid CSRF token'], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/generate-pdf', name: 'app_event_generate_pdf', methods: ['GET'])]
    public function generatePdfAction(Request $request, PdfGenerator $pdfGenerator, EventRepository $eventRepository): Response
    {
        $eventId = $request->query->get('eventId');
        $event = $eventRepository->find($eventId);

        if (!$event) {
            throw $this->createNotFoundException('Event not found');
        }

        $eventName = $event->getName();
        $reservationDate = $event->getBookingDate()->format('Y-m-d');
        $eventDate = $event->getEventDate()->format('Y-m-d');
        $numberOfAttendees = 5;
        $reservationUuid = "faut je cherche0";

        $pdfContent = $pdfGenerator->generatePdf($eventName, $eventDate, $reservationDate, $numberOfAttendees, $reservationUuid);

        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="reservation.pdf"'
        ]);
    }
}
