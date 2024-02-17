<?php

namespace Tests\Unit\Base\Mappers;

use App\Base\Mappers\PurchaseOrderStatus;
use Tests\TestCase;

class PurchaseOrderStatusTest extends TestCase
{
    /**
     * This is for actually testing the getAllConstants() method of the abstract class Mapper.
     *
     * @test
     */
    public function getAllConstants(): void
    {
        $expectedValues = [
            'OPEN' => 1,
            'RECEIVED' => 2,
            'INVOICED' => 3,
            'CANCELED' => 4,
        ];

        $this->assertEquals($expectedValues, PurchaseOrderStatus::getAllConstants());
    }
}
