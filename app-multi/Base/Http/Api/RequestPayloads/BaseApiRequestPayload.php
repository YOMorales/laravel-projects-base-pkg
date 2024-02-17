<?php

namespace App\Base\Http\Api\RequestPayloads;

use Exception;
use Illuminate\Support\Facades\Validator;

abstract class BaseApiRequestPayload
{
    // The next three properties are meant to be overridden by the child classes
    public string $payloadType = '';

    protected array $payloadStructure = [];

    protected array $payload = [];

    abstract public function build(): array;

    abstract public function getValidationRules(): array;

    public function validateData(): array
    {
        $validator = Validator::make(
            $this->payload,
            $this->getValidationRules()
        );

        if ($validator->fails()) {
            $errorMessages = json_encode($validator->errors()->all());
            $invalidData = json_encode($validator->invalid());
            throw new Exception(sprintf(
                'API Request Payload of type: %s. Errors: %s. Invalid data: %s',
                $this->payloadType,
                $errorMessages,
                $invalidData
            ));
        }

        return $this->payload;
    }
}
