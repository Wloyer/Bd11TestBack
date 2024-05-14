<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $data = json_decode($request->getContent(), true);

        // Utilisez le formulaire pour valider et assigner les données
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->submit($data);

        // Vérifiez manuellement si les termes sont acceptés
        if (!isset($data['agreeTerms']) || !$data['agreeTerms']) {
            return $this->json([
                'errors' => 'You should agree to our terms.',
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->json([
                'message' => 'User registered successfully',
            ], Response::HTTP_CREATED);
        }

        return $this->json([
            'errors' => (string) $form->getErrors(true, false),
        ], Response::HTTP_BAD_REQUEST);
    }
}
