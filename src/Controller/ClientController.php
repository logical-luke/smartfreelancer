<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Exception\InvalidPayloadException;
use App\Model\Client\ClientDTO;
use App\Model\Client\CreateClientPayload;
use App\Model\Client\DeleteClientPayload;
use App\Model\Client\UpdateClientPayload;
use App\Repository\ClientRepository;
use App\Service\Client\ClientCreator;
use App\Service\Client\ClientDeleter;
use App\Service\Client\ClientUpdater;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/client', name: 'app_client_')]
class ClientController extends AbstractController
{
    #[Route('/', name: 'all')]
    public function index(ClientRepository $clientRepository): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->json(array_map(static function ($client) {
            return (ClientDTO::fromClient($client));
        }, $clientRepository->findByUser($user)));
    }

    #[Route('/create', name: 'create', methods: 'POST')]
    public function create(ClientCreator $clientCreator, Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        try {
            $payload = array_merge([
                'ownerId' => $user->getId()->toRfc4122(),
            ], json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        } catch (JsonException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        try {
            return $this->json(
                ClientDTO::fromClient(
                    $clientCreator(CreateClientPayload::from($payload))
                )
            );
        } catch (InvalidPayloadException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/delete/{id}', name: 'delete', methods: 'DELETE')]
    public function delete(string $id, ClientDeleter $clientDeleter): JsonResponse
    {
        $clientDeleter(DeleteClientPayload::from(['id' => $id]));

        return $this->json([]);
    }

    #[Route('/update/{id}', name: 'update', methods: 'POST')]
    public function update(string $id, ClientUpdater $clientUpdater, Request $request): JsonResponse
    {
        try {
            $payload = array_merge([
                'id' => $id,
            ], json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        } catch (JsonException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $client = $clientUpdater(UpdateClientPayload::from($payload));

        return $this->json(
            ClientDTO::fromClient($client)
        );
    }

    #[Route('/{id}', name: 'show')]
    public function show(string $id, ClientRepository $clientRepository): Response
    {
        if (!$client = $clientRepository->find($id)) {
            return $this->json([], Response::HTTP_NOT_FOUND);
        }

        return $this->json(ClientDTO::fromClient($client));
    }
}
