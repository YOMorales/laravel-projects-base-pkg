<?php

namespace App\Transformers;

interface PipelineStageInterface
{
    /**
     * @param array $item
     * @return array
     */
    public function __invoke(array $item): array;
}
