<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\User;
use App\Repository\NotificationRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UserExtension extends AbstractExtension
{
    public function __construct(
        private readonly NotificationRepository $notificationRepository,
    ) {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('user_notifications', [$this, 'getNotifications']),
            new TwigFilter('user_unread_notifications', [$this, 'getUnreadNotifications']),
        ];
    }

    /**
     * @return array<mixed>
     */
    public function getNotifications(User $user): array
    {
        return $this->notificationRepository->findByUser($user);
    }

    /**
     * @return array<mixed>
     */
    public function getUnreadNotifications(User $user): array
    {
        return $this->notificationRepository->findByUser($user, true);
    }
}
