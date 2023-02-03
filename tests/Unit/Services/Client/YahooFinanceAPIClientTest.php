<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\Client;

use App\Services\YahooFinanceAPIClient;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class YahooFinanceAPIClientTest extends TestCase
{
    public const API_KEY = 'API_KEY';

    private HttpClientInterface|Mockery\LegacyMockInterface|Mockery\MockInterface $httpClient;
    private LoggerInterface|Mockery\LegacyMockInterface|Mockery\MockInterface $logger;
    private YahooFinanceAPIClient $client;

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testGetHistoricalData_success(): void
    {
        $companyName = 'AAPL';
        $responseAsArray = $this->getResponseAsArray();

        $response = Mockery::mock(ResponseInterface::class);
        $response->expects('getStatusCode')->andReturn(200);
        $response->expects('toArray')->andReturn($responseAsArray);

        $this->httpClient->expects('request')
            ->with(
                'GET',
                YahooFinanceAPIClient::BASE_URL . "/get-historical-data?symbol=$companyName",
                [
                    'headers' => [
                        'X-RapidAPI-Key' => self::API_KEY,
                        'X-RapidAPI-Host' => YahooFinanceAPIClient::HOST,
                    ]
                ]
            )
            ->andReturn($response);

        $expected = $responseAsArray;

        $actual = $this->client->getHistoricalData($companyName);
        self::assertEquals($actual, $expected);
    }

    public function testGetHistoricalData_successWithRegion(): void
    {
        $companyName = 'AAPL';
        $region = 'it';
        $responseAsArray = $this->getResponseAsArray();

        $response = Mockery::mock(ResponseInterface::class);
        $response->expects('getStatusCode')->andReturn(200);
        $response->expects('toArray')->andReturn($responseAsArray);

        $this->httpClient->expects('request')
            ->with(
                'GET',
                YahooFinanceAPIClient::BASE_URL . "/get-historical-data?symbol=$companyName&region=$region",
                [
                    'headers' => [
                        'X-RapidAPI-Key' => self::API_KEY,
                        'X-RapidAPI-Host' => YahooFinanceAPIClient::HOST,
                    ]
                ]
            )
            ->andReturn($response);

        $expected = $responseAsArray;
        $actual = $this->client->getHistoricalData($companyName, $region);
        self::assertEquals($actual, $expected);
    }

    public function testGetHistoricalData_responseCodeIsNotSuccess(): void
    {
        $companyName = 'AAPL';
        $region = 'it';
        $responseAsArray = $this->getResponseAsArray();

        $response = Mockery::mock(ResponseInterface::class);
        $response->expects('getStatusCode')->andReturn(400);
        $response->expects('toArray')->andReturn($responseAsArray);
        $response->expects('getInfo')->andReturn(['info' => 'info']);

        $this->httpClient->expects('request')
            ->with(
                'GET',
                YahooFinanceAPIClient::BASE_URL . "/get-historical-data?symbol=$companyName&region=$region",
                [
                    'headers' => [
                        'X-RapidAPI-Key' => self::API_KEY,
                        'X-RapidAPI-Host' => YahooFinanceAPIClient::HOST,
                    ]
                ]
            )
            ->andReturn($response);

        $this->logger->allows(
            'error'
        )->with(
            Mockery::on(
                static fn ($x) => \is_string($x)
            ),
            Mockery::on(
                static fn ($x) => \is_array($x)
            )
        );

        $actual = $this->client->getHistoricalData($companyName, $region);
        $expected = [];
        self::assertEquals($expected, $actual);
    }


    public function testGetHistoricalData_exception(): void
    {
        $companyName = 'AAPL';
        $region = 'it';

        $this->httpClient->expects('request')
            ->with(
                'GET',
                YahooFinanceAPIClient::BASE_URL . "/get-historical-data?symbol=$companyName&region=$region",
                [
                    'headers' => [
                        'X-RapidAPI-Key' => self::API_KEY,
                        'X-RapidAPI-Host' => YahooFinanceAPIClient::HOST,
                    ]
                ]
            )
            ->andThrow(new TransportException('Oops!'));

        $this->logger->allows('error')
            ->with(
                Mockery::on(
                    static fn ($x) => \is_string($x)
                ),
                Mockery::on(
                    static fn ($x) => \is_array($x)
                )
            );

        $actual = $this->client->getHistoricalData($companyName, $region);
        $expected = [];
        self::assertEquals($expected, $actual);
    }

    /**
     * @return array
     */
    public function getResponseAsArray(): array
    {
        return [
            'prices' => [
                [
                    'date' => 1675198804,
                    'open' => 1.809999942779541,
                    'high' => 1.8980000019073486,
                    'low' => 1.809999942779541,
                    'close' => 1.8600000143051147,
                    'volume' => 2112855,
                    'adjclose' => 1.8600000143051147,
                ],
                [
                    'date' => 1675089000,
                    'open' => 1.840000033378601,
                    'high' => 1.8799999952316284,
                    'low' => 1.809999942779541,
                    'close' => 1.809999942779541,
                    'volume' => 2866300,
                    'adjclose' => 1.809999942779541,
                ],
                [
                    'date' => 1674829800,
                    'open' => 1.840000033378601,
                    'high' => 1.9500000476837158,
                    'low' => 1.7599999904632568,
                    'close' => 1.850000023841858,
                    'volume' => 13234100,
                    'adjclose' => 1.850000023841858,
                ],
                [
                    'date' => 1674743400,
                    'open' => 1.8300000429153442,
                    'high' => 1.8700000047683716,
                    'low' => 1.7799999713897705,
                    'close' => 1.809999942779541,
                    'volume' => 6361500,
                    'adjclose' => 1.809999942779541,
                ],
                [
                    'date' => 1674657000,
                    'open' => 1.75,
                    'high' => 1.840000033378601,
                    'low' => 1.7000000476837158,
                    'close' => 1.7999999523162842,
                    'volume' => 12055500,
                    'adjclose' => 1.7999999523162842,
                ],
            ],
            'isPending' => false,
            'firstTradeDate' => 733674600,
            'id' => '',
            'timeZone' => ['gmtOffset' => -18000,],
            'eventsData' => [],
        ];
    }

    protected function setUp(): void
    {
        $this->httpClient = Mockery::mock(HttpClientInterface::class);
        $this->logger = Mockery::mock(LoggerInterface::class);
        $this->client = new YahooFinanceAPIClient(
            $this->httpClient,
            $this->logger,
            self::API_KEY
        );
        parent::setUp();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
