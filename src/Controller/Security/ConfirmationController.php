<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Event\Security\UserConfirmedEvent;
use App\Form\Security\ConfirmType;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConfirmationController extends AbstractController
{
    #[Route('/confirm/{email}', name: 'confirm', methods: ['GET', 'POST'])]
    public function __invoke(Request $request, User $user, EventDispatcherInterface $dispatcher): Response
    {
        $form = $this->createForm(ConfirmType::class, options: ['confirmation_code' => $user->getConfirmationCode()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $data */
            $data = $form->getData();

            if ($data->getConfirmationCode() === $user->getConfirmationCode()) {
                $dispatcher->dispatch(new UserConfirmedEvent($user));

                $this->addFlash('success', 'Your registration was successfully confirmed.');

                return $this->redirectToRoute('login');
            }
        }

        return $this->render('security/confirm.html.twig', [
            'form' => $form,
        ]);
    }
}
