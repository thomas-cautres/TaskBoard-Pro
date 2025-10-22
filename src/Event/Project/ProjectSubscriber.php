<?php

declare(strict_types=1);

namespace App\Event\Project;

use App\AppEnum\ProjectType;
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

        $user->setRoles(['ROLE_PROJECT_MANAGER']);
        $this->userRepository->persist($user);

        $project->setCreatedBy($user);

        switch ($project->getType()) {
            case ProjectType::Scrum:
                $project
                    ->addColumn(new ProjectColumn()->setName('Backlog'))
                    ->addColumn(new ProjectColumn()->setName('To Do'))
                    ->addColumn(new ProjectColumn()->setName('In Progress'))
                    ->addColumn(new ProjectColumn()->setName('Review'))
                    ->addColumn(new ProjectColumn()->setName('Done'));
                break;
            case ProjectType::Kanban:
                $project
                    ->addColumn(new ProjectColumn()->setName('To Do'))
                    ->addColumn(new ProjectColumn()->setName('In Progress'))
                    ->addColumn(new ProjectColumn()->setName('Done'));
                break;
            case ProjectType::Basic:
                $project
                    ->addColumn(new ProjectColumn()->setName('Open'))
                    ->addColumn(new ProjectColumn()->setName('Closed'));
                break;
        }

        $this->projectRepository->persist($project);
    }
}
