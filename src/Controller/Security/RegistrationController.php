<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Event\Security\UserRegisteredEvent;
use App\Form\Security\RegistrationType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'registration')]
    public function __invoke(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, EventDispatcherInterface $dispatcher): Response
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $dispatcher->dispatch(new UserRegisteredEvent($user));

            return $this->redirectToRoute('login');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form,
        ]);
    }
}
