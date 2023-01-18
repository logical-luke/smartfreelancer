<?php

namespace App\Controller;

use App\Entity\Project;
use App\Exception\InvalidPayloadException;
use App\Model\CreateProjectPayload;
use App\Model\DeleteProjectPayload;
use App\Model\ProjectDTO;
use App\Model\UpdateProjectPayload;
use App\Repository\ProjectRepository;
use App\Service\Project\ProjectCreator;
use App\Service\Project\ProjectDeleter;
use App\Service\Project\ProjectUpdater;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/project', name: 'app_project_')]
class ProjectController extends AbstractController
{
    #[Route('/', name: 'all')]
    public function index(ProjectRepository $projectRepository): JsonResponse
    {
        return $this->json(array_map(static function ($project) {
            return (ProjectDTO::fromProject($project));
        }, $projectRepository->findByUser($this->getUser())));
    }

    #[Route('/{id}', name: 'show')]
    public function show(Project $project): Response
    {
        return $this->json(ProjectDTO::fromProject($project));
    }

    #[Route('/create', name: 'create', methods: 'POST')]
    public function create(ProjectCreator $projectCreator, Request $request): JsonResponse
    {
        try {
            $payload = array_merge([
                'ownerId' => $this->getUser()->getId(),
            ], json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        } catch (JsonException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        try {
            return $this->json(
                ProjectDTO::fromProject(
                    $projectCreator(CreateProjectPayload::from($payload))
                )
            );
        } catch (InvalidPayloadException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/delete/{id}', name: 'delete', methods: 'DELETE')]
    public function delete(int $id, ProjectDeleter $projectDeleter): JsonResponse
    {
        $projectDeleter(DeleteProjectPayload::from(['id' => $id]));

        return $this->json([]);
    }

    #[Route('/update/{id}', name: 'update', methods: 'POST')]
    public function update(int $id, ProjectUpdater $projectUpdater, Request $request): JsonResponse
    {
        try {
            $payload = array_merge([
                'id' => $id,
            ], json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        } catch (JsonException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $project = $projectUpdater(UpdateProjectPayload::from($payload));

        return $this->json(
            ProjectDTO::fromProject($project)
        );
    }
}
