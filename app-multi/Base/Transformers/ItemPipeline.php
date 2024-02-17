<?php

namespace App\Base\Transformers;

/**
 * Iterates through a collection and passes each item to a series of stages (example: transformers).
 */
/**
 * This class is based on PHPLeague's Pipeline package.
 * https://github.com/thephpleague/pipeline
 *
 * But we are not using PHPLeague's Pipeline because that iterates the same dataset again and again
 * on each pipeline stage.
 *
 * Instead, this ItemPipeline class iterates the dataset once and passes each record to each stage.
 *
 * Note that the stages can anyway be called independently of ItemPipeline. For example, just do a foreach
 * on the dataset and pass each item to each transformer like:
 *
 * ```
 * $item = (new MapPurchaseStatusLabels())->__invoke($item);
 * $item = (new ChangeDateFormat('first_stocked', 'Y-m-d'))->__invoke($item);
 * ```
 *
 * Both approaches have almost the same performance.
 */
class ItemPipeline implements PipelineInterface
{
    /**
     * @var array
     */
    protected array $stages = [];

    public function pipe(PipelineStageInterface $stage): PipelineInterface
    {
        $pipeline = clone $this;
        $pipeline->stages[] = $stage;

        return $pipeline;
    }

    /**
     * @param array $dataset
     * @return array
     */
    public function process(array $dataset): array
    {
        foreach ($dataset as &$item) {
            foreach ($this->stages as $stage) {
                $item = $stage($item);
            }
        }

        return $dataset;
    }

    public function getStagesClassNames(): array
    {
        $classNames = [];

        foreach ($this->stages as $stage) {
            $classNames[] = $stage::class;
        }

        return $classNames;
    }
}
