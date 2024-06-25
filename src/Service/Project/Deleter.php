<?php

declare(strict_types=1);

namespace App\Service\Project;

use App\Entity\User;
use App\Model\Project\DeleteProjectPayload;
use App\Model\Synchronization\ActionPayloadInterface;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use App\Service\Synchronization\ProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'delete.project')]
readonly class Deleter implements ProcessorInterface
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private TaskRepository $taskRepository,
    ) {
    }

    public function sync(User $user, ActionPayloadInterface $payload): void
    {
        if (!$payload instanceof DeleteProjectPayload) {
            throw new \RuntimeException('Invalid payload');
        }

        if (!$project = $this->projectRepository->find($payload->getProjectId())) {
            throw new \RuntimeException('Project not found');
        }

        if (!$projectOwner = $project->getOwner()) {
            throw new \RuntimeException('Invalid project owner');
        }

        if ($user->getId()?->toRfc4122() !== $projectOwner->getId()?->toRfc4122()) {
            throw new \RuntimeException('Invalid project owner');
        }

        foreach ($project->getTasks() as $task) {
            $this->taskRepository->remove($task);
        }

        if ($timer = $project->getTimer()) {
            $timer->setProject(null);
        }

        foreach ($project->getTimeEntries() as $timeEntry) {
            $timeEntry->setProject(null);
        }

        $this->projectRepository->remove($project, true);
    }
}
