<?php

declare(strict_types=1);

namespace App\Controller\App\Notification;

use App\Entity\Notification;
use App\Event\Notification\NotificationReadEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/app/notification/{uuid}/read', name: 'notification_read', methods: ['GET'])]
class ReadNotificationController extends AbstractController
{
    public function __invoke(Notification $notification, EventDispatcherInterface $dispatcher): RedirectResponse
    {
        $dispatcher->dispatch(new NotificationReadEvent($notification));

        return $this->redirect($notification->getRedirectUrl());
    }
}
