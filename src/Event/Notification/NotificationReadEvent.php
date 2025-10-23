<?php

declare(strict_types=1);

namespace App\Event\Notification;

use App\Entity\Notification;
use Symfony\Contracts\EventDispatcher\Event;

class NotificationReadEvent extends Event
{
    public function __construct(private readonly Notification $notification)
    {
    }

    public function getNotification(): Notification
    {
        return $this->notification;
    }
}
