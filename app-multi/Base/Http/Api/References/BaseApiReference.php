<?php

namespace App\Base\Http\Api\References;

abstract class BaseApiReference
{
    protected array $data = [];

    /**
     * HTTP Method needed to call the API
     */
    abstract public function getMethod(): string;

    /**
     * API Path
     */
    abstract public function getPath(): string;

    /**
     * Http request body. GET requests are always empty.
     */
    public function body(): string
    {
        if ($this->getMethod() === 'GET') {
            return '';
        }

        return json_encode($this->data);
    }

    /**
     * Builds the final URL.
     */
    public function url(): string
    {
        $baseUrl = config('myapp.base_url');
        $path = $this->getPath();

        $queryString = '';
        if ($this->getMethod() === 'GET') {
            $queryString = '?' . http_build_query($this->data);
        }

        return $baseUrl . $path . $queryString;
    }
}
