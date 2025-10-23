<?php

declare(strict_types=1);

namespace App\Event\Project;

use App\AppEnum\ProjectType;
use App\Entity\Notification;
use App\Entity\Project;
use App\Entity\ProjectColumn;
use App\Entity\User;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class ProjectSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
        private UserRepository $userRepository,
        private ProjectRepository $projectRepository,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProjectCreatedEvent::class => 'onProjectCreated',
        ];
    }

    public function onProjectCreated(ProjectCreatedEvent $event): void
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $project = $event->getProject();

        $this->updateUserRoles($user);
        $project->setCreatedBy($user);

        match ($project->getType()) {
            ProjectType::Scrum => $this->addColumnsScrum($project),
            ProjectType::Kanban => $this->addColumnsKanban($project),
            ProjectType::Basic => $this->addColumnsBasic($project),
        };

        $user->addNotification(
            new Notification()->setContent(sprintf('Nouveau projet créé : %s', $project->getName()))
        );

        $this->userRepository->persist($user, flush: false);

        $this->projectRepository->persist($project);
    }

    private function updateUserRoles(User $user): void
    {
        if (in_array('ROLE_PROJECT_MANAGER', $user->getRoles(), true)) {
            return;
        }

        $currentRoles = $user->getRoles();
        $currentRoles[] = 'ROLE_PROJECT_MANAGER';
        $user->setRoles(array_unique($currentRoles));
    }

    private function addColumnsScrum(Project $project): void
    {
        $project
            ->addColumn(new ProjectColumn()->setName('Backlog')->setPosition(1))
            ->addColumn(new ProjectColumn()->setName('To Do')->setPosition(2))
            ->addColumn(new ProjectColumn()->setName('In Progress')->setPosition(3))
            ->addColumn(new ProjectColumn()->setName('Review')->setPosition(4))
            ->addColumn(new ProjectColumn()->setName('Done')->setPosition(5));
    }

    private function addColumnsKanban(Project $project): void
    {
        $project
            ->addColumn(new ProjectColumn()->setName('To Do')->setPosition(1))
            ->addColumn(new ProjectColumn()->setName('In Progress')->setPosition(2))
            ->addColumn(new ProjectColumn()->setName('Done')->setPosition(3));
    }

    private function addColumnsBasic(Project $project): void
    {
        $project
            ->addColumn(new ProjectColumn()->setName('Open')->setPosition(1))
            ->addColumn(new ProjectColumn()->setName('Closed')->setPosition(2));
    }
}
