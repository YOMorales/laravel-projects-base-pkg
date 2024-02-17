<?php

namespace App\MyApp\Http\Api\RequestPayloads;

use App\Base\Http\Api\RequestPayloads\BaseApiRequestPayload;

class ExampleOrderPayload extends BaseApiRequestPayload
{
    public string $payloadType = 'example_order';

    protected array $payloadStructure = [
        'order_number',
        'status',
        'example_field',
        'line_items',
    ];

    protected array $payload = [];

    public function __construct(protected array $rawOrderLines)
    {
        //
    }

    public function build(): array
    {
        $headers = $this->rawOrderLines[0];

        $structureAsKeys = array_fill_keys($this->payloadStructure, null);

        $this->payload = array_merge($structureAsKeys, array_intersect_key($headers, $structureAsKeys));

        foreach ($this->rawOrderLines as $rawLine) {
            $this->payload['line_items'][] = [
                'sku' => $rawLine['sku'],
                'line_item_number' => $rawLine['line_number'],
            ];
        }

        $this->validateData();

        return $this->payload;
    }

    public function getValidationRules(): array
    {
        return [
            'order_number' => ['string', 'required'],
            'status' => ['string', 'required'],
            'example_field' => ['integer', 'nullable'],
            'line_items' => ['array', 'required'],
            'line_items.*.sku' => ['string', 'required'],
            'line_items.*.line_item_number' => ['integer'],
        ];
    }
}
