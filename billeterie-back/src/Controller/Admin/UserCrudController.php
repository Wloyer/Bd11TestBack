<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

#[Route('/admin')]
class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Utilisateur')
            ->setDateFormat('yyyy-MM-dd') // Format de date personnalisé
            // Autres configurations éventuelles
        ;
    }

    public function configureFieldsUser(string $User): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('email'),
            TextField::new('roles'),
            TextField::new('password')
        ];
    }

    
    public function configureFieldsEvent(string $Event): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('name'),
            IntegerField::new('price'),
            DateField::new('eventHour'),
            DateField::new('bookingDate'),
            DateField::new('eventDate'),
            TextField::new('type'),
            TextField::new('description'),
            BooleanField::new('cancel'),
            BooleanField::new('nbTicket'),
            BooleanField::new('isSoldOut'),
            BooleanField::new('isAdult'),
            BooleanField::new('isGuestAdult'),
            TextField::new('location')
        ];
    }

    // public function configureFieldsSchedule(string $Schedule): iterable
    // {
    //     return [
    //         IdField::new('id'),
    //         IdField::new('idUser'),
    //         IdField::new('idEvent')
    //     ];
    // }

    #[Route('/createuser', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function createUser(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/deleteuser', name: 'app_user_delete', methods: ['POST'])]
    public function deleteUser(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/createevent', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function createEvent(Request $request, EntityManagerInterface $entityManager): Response
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

    #[Route('/deleteevent', name: 'app_event_delete', methods: ['POST'])]
    public function deleteEvent(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
    }


    // public function createEntitySchedule(string $entityFqcn)
    // {
    //     $schedule = new Schedule();
    //     $schedule->createdBy($this->getUser());

    //     return $schedule;
    // }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
