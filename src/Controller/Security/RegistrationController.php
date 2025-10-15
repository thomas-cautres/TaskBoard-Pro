<?php

declare(strict_types=1);

namespace App\Controller\Security;

use App\Entity\User;
use App\Event\Security\UserRegisteredEvent;
use App\Form\Security\RegistrationType;
use CoopTilleuls\UrlSignerBundle\UrlSigner\UrlSignerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'registration', methods: ['GET', 'POST'])]
    public function __invoke(
        Request $request,
        EventDispatcherInterface $dispatcher,
        UrlSignerInterface $urlSigner,
        #[Autowire(env: 'int:CONFIRMATION_LINK_LIFETIME')]
        int $confirmationLinkLifetime,
    ): Response {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $dispatcher->dispatch(new UserRegisteredEvent($user));

            $confirmUrl = $urlSigner->sign($this->generateUrl('confirm', ['email' => $user->getEmail()]), $confirmationLinkLifetime);

            return $this->redirect($confirmUrl);
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form,
        ]);
    }
}
