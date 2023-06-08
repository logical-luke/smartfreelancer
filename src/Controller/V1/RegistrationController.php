<?php

declare(strict_types=1);

namespace App\Controller\V1;

use App\Exception\InvalidPayloadException;
use App\Exception\UserAlreadyExistsException;
use App\Model\User\RegistrationPayload;
use App\Service\User\RegistrationHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/register', name: 'app_register_')]
class RegistrationController extends AbstractController
{
    #[Route('', name: 'app_index', methods: ['POST'])]
    public function index(Request $request, RegistrationHandler $registrationHandler): JsonResponse
    {
        try {
            $payload = RegistrationPayload::from(json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        } catch (\JsonException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        try {
            $registrationHandler($payload);
        } catch (InvalidPayloadException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (UserAlreadyExistsException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_CONFLICT);
        }

        return $this->json([]);
    }
}
