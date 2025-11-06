<?php

declare(strict_types=1);

namespace App\Event\Project;

use App\AppEnum\ProjectColumnName;
use App\AppEnum\ProjectType;
use App\Entity\Notification;
use App\Entity\Project;
use App\Entity\ProjectColumn;
use App\Entity\User;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class ProjectSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
        private UserRepository $userRepository,
        private ProjectRepository $projectRepository,
        private LoggerInterface $projectLogger,
        private RequestStack $requestStack,
        private UrlGeneratorInterface $urlGenerator,
        private TranslatorInterface $translator,
        private WorkflowInterface $projectStateMachine,
        private ObjectMapperInterface $objectMapper,
        private EntityManagerInterface $entityManager
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProjectCreatedEvent::class => 'onProjectCreated',
            ProjectEditedEvent::class => 'onProjectEdited',
            ProjectArchivedEvent::class => 'onProjectArchived',
            ProjectRestoredEvent::class => 'onProjectRestored',
        ];
    }

    public function onProjectCreated(ProjectCreatedEvent $event): void
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $projectDto = $event->getProject();

        $project = $this->objectMapper->map($projectDto, Project::class);

        $this->updateUserRoles($user);
        $project->setCreatedBy($user);

        match ($project->getType()) {
            ProjectType::Scrum => $this->addColumnsScrum($project),
            ProjectType::Kanban => $this->addColumnsKanban($project),
            ProjectType::Basic => $this->addColumnsBasic($project),
        };

        $user->addNotification(
            new Notification()
                ->setContent(sprintf($this->translator->trans('project.created.message').' : %s', $project->getName()))
                ->setRedirectUrl($this->urlGenerator->generate('app_project_show', ['uuid' => $project->getUuid()]))
        );

        $this->userRepository->save($user, flush: false);

        $this->projectRepository->save($project);
        $this->log($project, 'Project created', 'project.created');
    }

    public function onProjectEdited(ProjectEditedEvent $event): void
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $projectDto = $event->getProject();

        $project = $this->objectMapper->map($projectDto, $this->projectRepository->findOneBy(['uuid' => $projectDto->getUuid()]));

        $user->addNotification(
            new Notification()
                ->setContent(sprintf($this->translator->trans('project.edited.message').' : %s', $project->getName()))
                ->setRedirectUrl($this->urlGenerator->generate('app_project_show', ['uuid' => $project->getUuid()]))
        );

        $this->userRepository->save($user, flush: false);

        $this->projectRepository->save($project);
        $this->log($project, 'Project edited', 'project.edited');
    }

    public function onProjectArchived(ProjectArchivedEvent $event): void
    {
        $this->projectStateMachine->apply($event->getProject(), 'archive');
        $project = $this->objectMapper->map($event->getProject(), $this->projectRepository->findOneBy(['uuid' => $event->getProject()->getUuid()]));

        $this->projectRepository->save($project);
    }

    public function onProjectRestored(ProjectRestoredEvent $event): void
    {
        $this->projectStateMachine->apply($event->getProject(), 'restore');
        $project = $this->objectMapper->map($event->getProject(), $this->projectRepository->findOneBy(['uuid' => $event->getProject()->getUuid()]));

        $this->projectRepository->save($project);
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
            ->addColumn(new ProjectColumn()->setName(ProjectColumnName::BackLog->value)->setPosition(1))
            ->addColumn(new ProjectColumn()->setName(ProjectColumnName::ToDo->value)->setPosition(2))
            ->addColumn(new ProjectColumn()->setName(ProjectColumnName::InProgress->value)->setPosition(3))
            ->addColumn(new ProjectColumn()->setName(ProjectColumnName::Review->value)->setPosition(4))
            ->addColumn(new ProjectColumn()->setName(ProjectColumnName::Done->value)->setPosition(5));
    }

    private function addColumnsKanban(Project $project): void
    {
        $project
            ->addColumn(new ProjectColumn()->setName(ProjectColumnName::ToDo->value)->setPosition(1))
            ->addColumn(new ProjectColumn()->setName(ProjectColumnName::InProgress->value)->setPosition(2))
            ->addColumn(new ProjectColumn()->setName(ProjectColumnName::Done->value)->setPosition(3));
    }

    private function addColumnsBasic(Project $project): void
    {
        $project
            ->addColumn(new ProjectColumn()->setName(ProjectColumnName::Open->value)->setPosition(1))
            ->addColumn(new ProjectColumn()->setName(ProjectColumnName::Closed->value)->setPosition(2));
    }

    private function log(Project $project, string $message, string $action): void
    {
        $request = $this->requestStack->getCurrentRequest();

        $this->projectLogger->info($message, [
            'action' => $action,
            'user_id' => $project->getCreatedBy()->getId(),
            'created_at' => $project->getCreatedAt()->getTimestamp(),
            'project_name' => $project->getName(),
            'project_type' => $project->getType()->value,
            'project_start_date' => $project->getStartDate()?->format('Y-m-d'),
            'project_end_date' => $project->getEndDate()?->format('Y-m-d'),
            'ip' => (string) $request?->getClientIp(),
            'user-agent' => (string) $request?->headers->get('User-Agent'),
        ]);
    }
}
