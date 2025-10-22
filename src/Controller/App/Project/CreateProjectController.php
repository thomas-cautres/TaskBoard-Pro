<?php

declare(strict_types=1);

namespace App\Controller\App\Project;

use App\Entity\Project;
use App\Entity\User;
use App\Form\Project\CreateProjectType;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/app/project/create', name: 'app_project_create', methods: ['GET', 'POST'])]
class CreateProjectController extends AbstractController
{
    public function __invoke(Request $request, ProjectRepository $projectRepository, UserRepository $userRepository): Response
    {
        $project = new Project();
        $form = $this->createForm(CreateProjectType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();

            $user->setRoles(['ROLE_PROJECT_MANAGER']);
            $userRepository->persist($user, false);
            $projectRepository->persist($project->setUuid(Uuid::v7())->setCreatedBy($user)->setCreatedAt(new \DateTimeImmutable('now')));

            $this->addFlash('success', 'Projet créé avec succès');

            return $this->redirectToRoute('app_project_show', ['uuid' => $project->getUuid()]);
        }

        return $this->render('app/project/create_project.html.twig', [
            'form' => $form,
        ]);
    }
}
