<?php

namespace App\MyApp\Transformers;

use App\Base\Mappers\PurchaseOrderStatus;
use App\Base\Transformers\PipelineStageInterface;

class MapPurchaseStatusLabels implements PipelineStageInterface
{
    /**
     * @param array $item
     * @return array
     */
    public function __invoke(array $item): array
    {
        $item['status'] = PurchaseOrderStatus::$statusLabels[$item['po_status_code']] ?? '';

        return $item;
    }
}
