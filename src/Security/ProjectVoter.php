<?php

declare(strict_types=1);

namespace App\Security;

use App\Dto\View\Project\AbstractProjectModel;
use App\Dto\View\Project\ProjectModel;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Workflow\WorkflowInterface;

/** @extends Voter<string, ProjectModel> */
class ProjectVoter extends Voter
{
    public const string VIEW = 'view';
    public const string EDIT = 'edit';
    public const string ARCHIVE = 'archive';
    public const string RESTORE = 'restore';

    public function __construct(
        private readonly WorkflowInterface $projectStateMachine,
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::ARCHIVE, self::RESTORE])) {
            return false;
        }

        if (!$subject instanceof AbstractProjectModel) {
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

        /** @var AbstractProjectModel $project */
        $project = $subject;

        return match ($attribute) {
            self::VIEW => $this->canView($project, $user),
            self::EDIT => $this->canEdit($project, $user),
            self::ARCHIVE => $this->canArchive($project, $user),
            self::RESTORE => $this->canRestore($project, $user),
            default => throw new \LogicException('This code should not be reached!'),
        };
    }

    private function canView(AbstractProjectModel $project, User $user): bool
    {
        return $this->loggedUserIsProjectCreator($user, $project->getCreatedByEmail());
    }

    private function canEdit(AbstractProjectModel $project, User $user): bool
    {
        return $this->loggedUserIsProjectCreator($user, $project->getCreatedByEmail());
    }

    private function canArchive(AbstractProjectModel $project, User $user): bool
    {
        return $this->loggedUserIsProjectCreator($user, $project->getCreatedByEmail()) && true === $this->projectStateMachine->can($project, 'archive');
    }

    private function canRestore(AbstractProjectModel $project, User $user): bool
    {
        return $this->loggedUserIsProjectCreator($user, $project->getCreatedByEmail()) && true === $this->projectStateMachine->can($project, 'restore');
    }

    private function loggedUserIsProjectCreator(User $user, string $createdByEmail): bool
    {
        return $user->getEmail() === $createdByEmail;
    }
}
