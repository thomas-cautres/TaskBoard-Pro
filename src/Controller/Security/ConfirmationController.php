<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\Security\ConfirmType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConfirmationController extends AbstractController
{
    #[Route('/confirm/{email}', name: 'confirm', methods: ['GET', 'POST'])]
    public function __invoke(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(ConfirmType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $data */
            $data = $form->getData();

            if ($data->getConfirmationCode() === $user->getConfirmationCode()) {
                $userRepository->persist($user->setConfirmed(true));

                return $this->redirectToRoute('login');
            }
        }

        return $this->render('security/confirm.html.twig', [
            'form' => $form,
        ]);
    }
}
