<?php

namespace App\Base\Http\Api;

use App\Base\Exceptions\BaseApiException;
use Illuminate\Http\Client\Response as LaravelResponse;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;

class BaseApiResponse extends LaravelResponse
{
    public function __construct(protected ResponseInterface $psrResponse)
    {
    }

    public function json($path = null, $default = null): mixed
    {
        $json = json_decode($this->psrResponse->getBody(), true, 512, JSON_THROW_ON_ERROR);

        if ($path === null) {
            return $json;
        }

        return Arr::get($json, $path, $default);
    }

    /**
     * Error handling for this API is mostly done by way of the exceptions thrown
     * here and resolved in app/Exceptions/Handler.php.
     *
     * @return void
     */
    public function throw(): void
    {
        $statusCode = $this->psrResponse->getStatusCode();
        if ($statusCode >= 200 && $statusCode < 300) {
            return;
        }

        $responseData = $this->json();

        if (isset($responseData['error'])) {
            $errorMessage = sprintf(
                "%s: %s\nURL: %s",
                $responseData['error']['status'],
                $responseData['error']['message'],
                $responseData['self']
            );
        } else {
            $errorMessage = sprintf(
                "%s\nURL: %s",
                'Unexpected response without an error message.',
                $responseData['self']
            );
        }

        throw new BaseApiException($errorMessage, $statusCode);
    }
}
