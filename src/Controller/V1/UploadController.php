<?php

declare(strict_types=1);

namespace App\Controller\V1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        Request $request
    ): JsonResponse {
        $file = $request->files->get('image');

        if (!$file instanceof UploadedFile) {
            return $this->json([
                'error' => 'No file has been uploaded.',
            ], Response::HTTP_BAD_REQUEST);
        }

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $originalFilename . '-' . uniqid('', true) . '.' . $file->guessExtension();

        try {
            $file->move('uploads', $newFilename);
        } catch (\Exception $exception) {
            return $this->json([
                'error' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json([
            'filename' => $newFilename,
        ]);
    }
}
