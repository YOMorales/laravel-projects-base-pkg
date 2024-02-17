<?php

namespace App\Http\Api;

use App\Http\Api\BaseApiResponse;
use App\Http\Api\References\BaseApiReference;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class MyAppApiClient
{
    protected array $commonHeaders = [
        'Content-Type' => 'application/json',
        'User-Agent' => 'myapp-client/1.0 (Language=PHP)',
    ];

    public function __construct()
    {
        //
    }

    /**
     * @param BaseApiReference $reference
     * @return BaseApiResponse
     */
    public function call(BaseApiReference $reference): BaseApiResponse
    {
        $accessToken = $this->getAccessToken();

        try {
            $response = Http::withToken($accessToken)
                ->timeout(20)
                ->retry(2, 2000, function (Throwable $exception) {
                    if ($exception instanceof ConnectionException) {
                        return true;
                    }
                    if ($exception instanceof RequestException) {
                        return $exception->response->status() >= 500;
                    }
                    return false;
                })
                ->withHeaders($this->commonHeaders)
                ->withBody($reference->body(), 'application/json')
                ->send($reference->getMethod(), $reference->url());
        } catch (RequestException $th) {
            /*
            Errors in the above API call will not set the $response variable.
            So here we set it using the response that is contained within RequestException.
            */
            $response = $th->response;
        } finally {
            return new BaseApiResponse($response->toPsrResponse());
        }
    }

    /**
     * @return string
     */
    protected function getAccessToken(): string
    {
        $args = [
            'client_id' => config('myapp.client_id'),
            'client_secret' => config('myapp.client_secret'),
            'grant_type' => config('myapp.grant_type'),
            'audience' => config('myapp.audience'),
        ];

        $cacheKey = md5(implode(':', $args));
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $response = Http::timeout(10)
            ->retry(3, 2000)
            ->withHeaders($this->commonHeaders)
            ->post(config('myapp.token_url'), $args);

        $response->throw();
        $data = $response->json();

        $accessToken = $data['access_token'];
        $expiresIn = intval($data['expires_in']);

        Cache::put($cacheKey, $accessToken, $expiresIn);

        return $accessToken;
    }
}
