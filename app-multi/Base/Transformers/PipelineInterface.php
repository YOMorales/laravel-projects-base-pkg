<?php

namespace App\Base\Transformers;

interface PipelineInterface
{
    public function pipe(PipelineStageInterface $stage): PipelineInterface;

    /**
     * @param array $dataset
     * @return array
     */
    public function process(array $dataset): array;

    /**
     * @return array
     */
    public function getStagesClassNames(): array;
}
