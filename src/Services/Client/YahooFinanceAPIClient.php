<?php

declare(strict_types=1);

namespace App\Services\Client;

use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\ApcuAdapter;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class YahooFinanceAPIClient implements FinanceApiClient
{
    private HttpClientInterface $httpClient;
    private string $apiKey;
    private LoggerInterface $logger;
    private string $yahooFinanceApiURL;

    public function __construct(
        HttpClientInterface $httpClient,
        LoggerInterface $logger,
        string $yahooFinanceApiURL,
        string $apiKey
    ) {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
        $this->yahooFinanceApiURL = $yahooFinanceApiURL;
        $this->apiKey = $apiKey;
    }

    public function getHistoricalData(string $companyName, ?string $region = null): array
    {
        $regionParameter = $region !== null ? "&region=$region" : '';
        $url = $this->yahooFinanceApiURL . "/stock/v3/get-historical-data?symbol=$companyName" . $regionParameter;
        try {
            $response = $this->httpClient->request(
                'GET',
                $url,
                [
                    'headers' => [
                        'X-RapidAPI-Key' => $this->apiKey,
                        'X-RapidAPI-Host' => 'yh-finance.p.rapidapi.com',
                    ]
                ]
            );
            $statusCode = $response->getStatusCode();
            $responseAsArray = $response->toArray();
        } catch (\Exception $e) {
            $this->logger->error(
                $e::class,
                [
                    'URL' => $url,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]
            );
            throw $e;
        }

        if ($statusCode !== 200) {
            $this->logger->error(
                'Request failed',
                [
                    'URL' => $url,
                    'data' => $response->getInfo(),
                    'responseCode' => $statusCode
                ]
            );
            throw new \HttpResponseException('Incorrect status code');
        }

        return $responseAsArray;
    }
}
