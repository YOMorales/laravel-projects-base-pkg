<?php

namespace App\Base\Mappers;

class PurchaseOrderStatus extends Mapper
{
    public const OPEN = 1;
    public const RECEIVED = 2;
    public const INVOICED = 3;
    public const CANCELED = 4;

    /**
     * @var array<int,string>
     */
    public static array $statusLabels = [
        1 => 'Open',
        2 => 'Received',
        3 => 'Invoiced',
        4 => 'Canceled',
    ];
}
