<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\InvalidPayloadException;
use App\Model\Project\CreateProjectPayload;
use App\Model\Project\DeleteProjectPayload;
use App\Model\Project\ProjectDTO;
use App\Model\Project\UpdateProjectPayload;
use App\Repository\ProjectRepository;
use App\Service\Project\ProjectCreator;
use App\Service\Project\ProjectDeleter;
use App\Service\Project\ProjectUpdater;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/project', name: 'app_project_')]
class ProjectController extends AbstractController
{
    #[Route('', name: 'all')]
    public function index(ProjectRepository $projectRepository): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->json(array_map(static function ($project) {
            return ProjectDTO::fromProject($project);
        }, $projectRepository->findByUser($user)));
    }

    #[Route('/create', name: 'create', methods: 'POST')]
    public function create(ProjectCreator $projectCreator, Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        try {
            $payload = array_merge([
                'ownerId' => $user->getId()?->toRfc4122(),
            ], json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        } catch (\JsonException $e) {
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

    #[Route('/delete', name: 'delete_bulk', methods: 'DELETE')]
    public function deleteBulk(Request $request, ProjectDeleter $projectDeleter): JsonResponse
    {
        // todo Add check if user is eligible to delete project
        try {
            $ids = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        array_walk($ids, static fn ($id) => $projectDeleter(DeleteProjectPayload::from(['id' => $id])));

        return $this->json([]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: 'DELETE')]
    public function delete(string $id, ProjectDeleter $projectDeleter): JsonResponse
    {
        // todo Add check if user is eligible to delete project

        $projectDeleter(DeleteProjectPayload::from(['id' => $id]));

        return $this->json([]);
    }

    #[Route('/update/{id}', name: 'update', methods: 'POST')]
    public function update(string $id, ProjectUpdater $projectUpdater, Request $request): JsonResponse
    {
        try {
            $payload = array_merge([
                'id' => $id,
            ], json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        } catch (\JsonException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $project = $projectUpdater(UpdateProjectPayload::from($payload));

        return $this->json(
            ProjectDTO::fromProject($project)
        );
    }

    #[Route('/{id}', name: 'show', methods: 'GET')]
    public function show(string $id, ProjectRepository $projectRepository): Response
    {
        if (!$project = $projectRepository->find($id)) {
            return $this->json([], Response::HTTP_NOT_FOUND);
        }

        return $this->json(ProjectDTO::fromProject($project));
    }
}
