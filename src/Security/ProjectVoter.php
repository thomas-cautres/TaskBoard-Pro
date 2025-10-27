<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\Project;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/** @extends Voter<string, Project> */
class ProjectVoter extends Voter
{
    public const string VIEW = 'view';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::VIEW])) {
            return false;
        }

        if (!$subject instanceof Project) {
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

        /** @var Project $project */
        $project = $subject;

        return match ($attribute) {
            self::VIEW => $this->canView($project, $user),
            default => throw new \LogicException('This code should not be reached!'),
        };
    }

    private function canView(Project $project, User $user): bool
    {
        return $user === $project->getCreatedBy();
    }
}
