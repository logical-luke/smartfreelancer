<?php

declare(strict_types=1);

namespace App\Controller\V1;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/upload', name: 'app_upload_')]
class UploadController extends AbstractController
{
    #[Route('/image', name: 'image', methods: ['POST'])]
    public function uploadImage(
        Request $request,
        ParameterBagInterface $params,
    ): JsonResponse {
        /** @var User $user */
        $user = $this->getUser();

        $file = $request->files->get('image');

        if (!$file instanceof UploadedFile) {
            return $this->json([
                'error' => 'No file has been uploaded.',
            ], Response::HTTP_BAD_REQUEST);
        }

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        if (!$user->getId()) {
            return $this->json([
                'error' => 'User is not authenticated.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $newFilename = sprintf('%s-%s-%s.%s', $user->getId()->toRfc4122(), $originalFilename, uniqid('', true), $file->guessExtension());

        try {
            $file->move(sprintf('%s/upload', $params->get('kernel.project_dir')), $newFilename);
        } catch (\Exception $exception) {
            return $this->json([
                'error' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json([
            'filename' => sprintf('%s/%s',  $params->get('uploaded_files_base_url'), $newFilename),
        ]);
    }
}
