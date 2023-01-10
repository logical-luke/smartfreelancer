<?php

namespace App\Controller;

use App\Model\CreateProjectPayload;
use App\Model\ProjectDTO;
use App\Repository\ProjectRepository;
use App\Service\Project\ProjectCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    #[Route('/project', name: 'app_project')]
    public function index(ProjectRepository $projectRepository): JsonResponse
    {
        return $this->json($projectRepository->findAll());
    }

    #[Route('/project/create', name: 'app_project_create', methods: 'POST')]
    public function create(ProjectCreator $projectCreator, Request $request): JsonResponse
    {
        try {
            $payload = array_merge([
                'ownerId' => $this->getUser()->getId(),
            ], json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        } catch (\JsonException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(
            ProjectDTO::fromProject(
                $projectCreator(CreateProjectPayload::from($payload))
            )
        );
    }
}
