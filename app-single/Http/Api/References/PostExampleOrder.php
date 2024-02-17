<?php

namespace App\Http\Api\References;

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
