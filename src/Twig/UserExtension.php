<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\User;
use App\Repository\NotificationRepository;
use Twig\Attribute\AsTwigFilter;

readonly class UserExtension
{
    public function __construct(
        private NotificationRepository $notificationRepository,
    ) {
    }

    /**
     * @return array<mixed>
     */
    #[AsTwigFilter('user_notifications')]
    public function getNotifications(User $user): array
    {
        return $this->notificationRepository->findByUser($user);
    }

    #[AsTwigFilter('user_unread_notifications_count')]
    public function getUnreadNotificationsCount(User $user): int
    {
        return $this->notificationRepository->countByUser($user, true);
    }
}
