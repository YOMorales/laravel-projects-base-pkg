<?php

namespace App\Transformers;

use App\Mappers\PurchaseOrderStatus;

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
