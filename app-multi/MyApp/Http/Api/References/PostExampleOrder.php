<?php

namespace App\MyApp\Http\Api\References;

use App\Base\Http\Api\References\BaseApiReference;

final class PostExampleOrder extends BaseApiReference
{
    public function __construct(protected array $data)
    {
    }

    /** @inheritdoc */
    public function getMethod(): string
    {
        return 'POST';
    }

    /** @inheritdoc */
    public function getPath(): string
    {
        return '/example_orders';
    }
}
