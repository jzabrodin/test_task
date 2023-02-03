<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services;

use App\Services\Client\FinanceApiClient;
use App\Services\GetCompanyHistoricalDataFromAPIService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

class GetCompanyHistoricalDataFromAPIServiceTest extends TestCase
{
    /**
     * @var FinanceApiClient|(FinanceApiClient&\Mockery\LegacyMockInterface)|(FinanceApiClient&\Mockery\MockInterface)|\Mockery\LegacyMockInterface|\Mockery\MockInterface
     */
    private FinanceApiClient|\Mockery\MockInterface|\Mockery\LegacyMockInterface $financeApiClient;

    private function getApiClientFinancialData(): array
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

                    [
                        'date' => 1674570600,
                        'open' => 1.7999999523162842,
                        'high' => 1.809999942779541,
                        'low' => 1.690000057220459,
                        'close' => 1.75,
                        'volume' => 9707100,
                        'adjclose' => 1.75,
                    ],

                    [
                        'date' => 1674484200,
                        'open' => 1.8300000429153442,
                        'high' => 1.8899999856948853,
                        'low' => 1.7599999904632568,
                        'close' => 1.7999999523162842,
                        'volume' => 7143900,
                        'adjclose' => 1.7999999523162842,
                    ],

                    [
                        'date' => 1674225000,
                        'open' => 1.8600000143051147,
                        'high' => 1.9299999475479126,
                        'low' => 1.7699999809265137,
                        'close' => 1.8200000524520874,
                        'volume' => 7833100,
                        'adjclose' => 1.8200000524520874,
                    ],

                    [
                        'date' => 1674138600,
                        'open' => 1.8200000524520874,
                        'high' => 1.9199999570846558,
                        'low' => 1.75,
                        'close' => 1.840000033378601,
                        'volume' => 4732000,
                        'adjclose' => 1.840000033378601,
                    ],

                    [
                        'date' => 1674052200,
                        'open' => 1.940000057220459,
                        'high' => 2,
                        'low' => 1.8300000429153442,
                        'close' => 1.850000023841858,
                        'volume' => 4346800,
                        'adjclose' => 1.850000023841858,
                    ],

                    [
                        'date' => 1673965800,
                        'open' => 2.0999999046325684,
                        'high' => 2.0999999046325684,
                        'low' => 1.899999976158142,
                        'close' => 1.9700000286102295,
                        'volume' => 9215400,
                        'adjclose' => 1.9700000286102295,
                    ],

                    [
                        'date' => 1673620200,
                        'open' => 1.909999966621399,
                        'high' => 2.130000114440918,
                        'low' => 1.909999966621399,
                        'close' => 2.0899999141693115,
                        'volume' => 6737400,
                        'adjclose' => 2.0899999141693115,
                    ],

                    [
                        'date' => 1673533800,
                        'open' => 1.850000023841858,
                        'high' => 1.9700000286102295,
                        'low' => 1.7200000286102295,
                        'close' => 1.9500000476837158,
                        'volume' => 5145100,
                        'adjclose' => 1.9500000476837158,
                    ],

                    [
                        'date' => 1673447400,
                        'open' => 1.7799999713897705,
                        'high' => 1.8600000143051147,
                        'low' => 1.7000000476837158,
                        'close' => 1.8200000524520874,
                        'volume' => 7044700,
                        'adjclose' => 1.8200000524520874,
                    ],

                    [
                        'date' => 1673361000,
                        'open' => 1.5800000429153442,
                        'high' => 1.850000023841858,
                        'low' => 1.5499999523162842,
                        'close' => 1.7200000286102295,
                        'volume' => 10291900,
                        'adjclose' => 1.7200000286102295,
                    ],

                    [
                        'date' => 1673274600,
                        'open' => 1.440000057220459,
                        'high' => 1.5399999618530273,
                        'low' => 1.4299999475479126,
                        'close' => 1.4800000190734863,
                        'volume' => 2930100,
                        'adjclose' => 1.4800000190734863,
                    ],

                    [
                        'date' => 1673015400,
                        'open' => 1.3300000429153442,
                        'high' => 1.4500000476837158,
                        'low' => 1.309999942779541,
                        'close' => 1.440000057220459,
                        'volume' => 5739800,
                        'adjclose' => 1.440000057220459,
                    ],

                    [
                        'date' => 1672929000,
                        'open' => 1.2300000190734863,
                        'high' => 1.2799999713897705,
                        'low' => 1.2100000381469727,
                        'close' => 1.2599999904632568,
                        'volume' => 2248200,
                        'adjclose' => 1.2599999904632568,
                    ],

                    [
                        'date' => 1672842600,
                        'open' => 1.1699999570846558,
                        'high' => 1.25,
                        'low' => 1.1699999570846558,
                        'close' => 1.2400000095367432,
                        'volume' => 1147400,
                        'adjclose' => 1.2400000095367432,
                    ],

                    [
                        'date' => 1672756200,
                        'open' => 1.2100000381469727,
                        'high' => 1.25,
                        'low' => 1.149999976158142,
                        'close' => 1.190000057220459,
                        'volume' => 1838300,
                        'adjclose' => 1.190000057220459,
                    ],
                ],
            'isPending' => false,
            'firstTradeDate' => 733674600,
            'id' => '',
            'timeZone' => ['gmtOffset' => -18000,],
            'eventsData' => [],
        ];
    }

    /**
     * @throws ServerExceptionInterface
     * @throws ExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testGetCompanyHistoricalData_success(): void
    {
        $region = 'GB';
        $companyName = 'AAPL';
        $expected = [];

        $this->financeApiClient->expects('getHistoricalData')
            ->with()
            ->andReturn();

        $actual = $this->service->getCompanyHistoricalData(
            $companyName,
            \DateTime::createFromFormat('Y-m-d', '2020-02-22'),
            \DateTime::createFromFormat('Y-m-d', '2023-02-22'),
            $region
        );

        self::assertEquals($expected, $actual);
    }

    protected function setUp(): void
    {
        $this->financeApiClient = \Mockery::mock(FinanceApiClient::class);
        $this->serializer = \Mockery::mock(SerializerInterface::class);

        $this->service = new GetCompanyHistoricalDataFromAPIService($this->financeApiClient);

        parent::setUp(); // TODO: Change the autogenerated stub
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown(); // TODO: Change the autogenerated stub
    }
}
