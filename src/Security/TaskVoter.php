<?php

declare(strict_types=1);

namespace App\Security;

use App\Dto\Task\TaskDto;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/** @extends Voter<string, TaskDto> */
class TaskVoter extends Voter
{
    public const string CREATE = 'create';
    public const string VIEW = 'view';
    public const string EDIT = 'edit';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::CREATE])) {
            return false;
        }

        if (!$subject instanceof TaskDto) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var TaskDto $task */
        $task = $subject;

        return match ($attribute) {
            self::EDIT => $this->canEdit($task, $user),
            self::VIEW => $this->canView($task, $user),
            self::CREATE => $this->canCreate($task, $user),
            default => throw new \LogicException('This code should not be reached!'),
        };
    }

    private function canCreate(TaskDto $task, User $user): bool
    {
        return true;
    }

    private function canEdit(TaskDto $task, User $user): bool
    {
        return true;
    }

    private function canView(TaskDto $task, User $user): bool
    {
        return true;
    }
}
