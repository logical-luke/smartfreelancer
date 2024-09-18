<?php

declare(strict_types=1);

namespace App\Controller\V1;

use App\Entity\Client;
use App\Entity\User;
use App\Enum\ExceptionErrors;
use App\Exception\ForbiddenActionException;
use App\Exception\InvalidPayloadException;
use App\Exception\ResourceNotFoundException;
use App\Model\Client\ClientDto;
use App\Model\Client\CreateClientPayload;
use App\Model\Client\DeleteClientPayload;
use App\Model\Client\UpdateClientPayload;
use App\Repository\ClientRepository;
use App\Service\Client\ClientDtoArrayMapper;
use App\Service\Client\Creator;
use App\Service\Client\Deleter;
use App\Service\Client\Updater;
use InvalidArgumentException;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/clients', name: 'app_client_')]
class ClientsController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(ClientDtoArrayMapper $clientDtoArrayMapper): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->json($clientDtoArrayMapper->map($user->getClients()));
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Creator $clientCreator, Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        try {
            $client = ($clientCreator)(CreateClientPayload::from(
                $user,
                json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR)
            ));
        } catch (InvalidPayloadException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        } catch (JsonException $e) {
            return $this->json(['error' => ExceptionErrors::UNABLE_TO_PARSE_JSON], 400);
        }

        return $this->json(ClientDto::fromClient($client, 0, 0, 0, 0, 0, 0));
    }

    #[Route('/{id}', name: 'read', methods: ['GET'])]
    public function read(): JsonResponse
    {
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(Client $client, Updater $updater, Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        try {
            $client = ($updater)
            (UpdateClientPayload::from(json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR), $user));
        } catch (InvalidPayloadException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        } catch (JsonException $e) {
            return $this->json(['error' => ExceptionErrors::UNABLE_TO_PARSE_JSON], 400);
        } catch (ResourceNotFoundException $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        } catch (ForbiddenActionException $e) {
            return $this->json(['error' => $e->getMessage()], 403);
        }

        return $this->json(ClientDto::fromClient($client, 0, 0, 0, 0, 0, 0));
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Client $client, Deleter $deleter): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        try {
            ($deleter)(DeleteClientPayload::from($client, $user));
        } catch (ResourceNotFoundException $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        } catch (ForbiddenActionException $e) {
            return $this->json(['error' => $e->getMessage()], 403);
        }

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
