<?php

declare(strict_types=1);

namespace App\Controller\V1;

use App\Entity\Project;
use App\Entity\User;
use App\Enum\ExceptionErrors;
use App\Exception\ForbiddenActionException;
use App\Exception\InvalidPayloadException;
use App\Exception\ResourceNotFoundException;
use App\Model\Project\ProjectDto;
use App\Model\Project\CreateProjectPayload;
use App\Model\Project\DeleteProjectPayload;
use App\Model\Project\UpdateProjectPayload;
use App\Repository\ProjectRepository;
use App\Service\Project\ProjectDtoArrayMapper;
use App\Service\Project\ProjectDtoFactory;
use App\Service\Project\Creator;
use App\Service\Project\Deleter;
use App\Service\Project\Updater;
use InvalidArgumentException;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/projects', name: 'app_project_')]
class ProjectsController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(ProjectDtoArrayMapper $projectDtoArrayMapper): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->json($projectDtoArrayMapper->map($user->getProjects()));
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Creator $projectCreator, Request $request, ProjectDtoFactory $projectDtoFactory): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        try {
            $project = ($projectCreator)(CreateProjectPayload::from(
                $user,
                json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR)
            ));
        } catch (InvalidPayloadException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        } catch (JsonException $e) {
            return $this->json(['error' => ExceptionErrors::UNABLE_TO_PARSE_JSON], 400);
        }

        return $this->json($projectDtoFactory($project));
    }

    #[Route('/{id}', name: 'read', methods: ['GET'])]
    public function read(Project $project, ProjectDtoFactory $projectDtoFactory): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user->getId() !== $project->getOwner()->getId()) {
            return $this->json(['error' => ExceptionErrors::FORBIDDEN_ACTION], 403);
        }

        return $this->json($projectDtoFactory($project));
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(Project $project, Updater $updater, Request $request, ProjectDtoFactory $projectDtoFactory): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        try {
            $project = ($updater)
            (UpdateProjectPayload::from($project, json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR), $user));
        } catch (InvalidPayloadException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        } catch (JsonException $e) {
            return $this->json(['error' => ExceptionErrors::UNABLE_TO_PARSE_JSON], 400);
        } catch (ResourceNotFoundException $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        } catch (ForbiddenActionException $e) {
            return $this->json(['error' => $e->getMessage()], 403);
        }

        return $this->json($projectDtoFactory($project));
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Project $project, Deleter $deleter): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        try {
            ($deleter)(DeleteProjectPayload::from($project, $user));
        } catch (ResourceNotFoundException $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        } catch (ForbiddenActionException $e) {
            return $this->json(['error' => $e->getMessage()], 403);
        }

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
