<?php

declare(strict_types=1);

namespace App\Service\Synchronization;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class ProcessorsCollection
{
    private iterable $processors;

    public function __construct(
        #[TaggedIterator('app.synchronization_processor', indexAttribute: 'key')]
        iterable $processors,
    ) {
        $this->processors = $processors instanceof \Traversable ? iterator_to_array($processors) : $processors;
    }

    public function get(string $key): ProcessorInterface
    {
        if (!$this->exist($key)) {
            throw new \RuntimeException(sprintf('Processor with key "%s" not found', $key));
        }

        return $this->processors[$key];
    }

    public function exist(string $key): bool
    {
        return isset($this->processors[$key]);
    }
}
