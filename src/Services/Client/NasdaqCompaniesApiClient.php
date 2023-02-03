<?php

declare(strict_types=1);

namespace App\Services\Client;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class NasdaqCompaniesApiClient implements NasdaqCompaniesInfoClient
{
    private HttpClientInterface $httpClient;
    private LoggerInterface $logger;
    private string $url;

    public function __construct(
        HttpClientInterface $httpClient,
        LoggerInterface $logger,
        string $url
    ) {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
        $this->url = $url;
    }

    public function getNasdaqListedCompaniesResponse(): ?ResponseInterface
    {
        try {
            $response = $this->httpClient->request(
                'GET',
                $this->url
            );
        } catch (TransportExceptionInterface $e) {
            $this->logger->error(
                'NasdaqCompaniesApiClient::getNasdaqListedCompanies error occurred!',
                [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]
            );

            $response = null;
        }

        if ($response !== null && $response->getStatusCode() !== 200) {
            $this->logger->warning(
                'NasdaqCompaniesApiClient::getNasdaqListedCompanies response code is not 200',
                [
                    'responseCode' => $response->getStatusCode(),
                    'info' => $response->getContent()
                ]
            );

            $response = null;
        }

        return $response;
    }
}
