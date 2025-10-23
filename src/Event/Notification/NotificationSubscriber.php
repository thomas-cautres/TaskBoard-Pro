<?php

declare(strict_types=1);

namespace App\Event\Notification;

use App\Repository\NotificationRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class NotificationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private NotificationRepository $notificationRepository,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            NotificationReadEvent::class => 'onNotificationRead',
        ];
    }

    public function onNotificationRead(NotificationReadEvent $event): void
    {
        $notification = $event->getNotification();
        $notification->setReadAt(new \DateTimeImmutable());

        $this->notificationRepository->persist($notification);
    }
}
