<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Client\FinanceApiClient;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

class GetCompanyHistoricalDataFromAPIService implements GetCompanyHistoricalDataByStartAndEndDateInterface
{
    private FinanceApiClient $financeApiClient;

    public function __construct(
        FinanceApiClient $financeApiClient,
    ) {
        $this->financeApiClient = $financeApiClient;
    }

    /**
     * @param string $companyName
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param string|null $region
     * @return array
     */
    public function getCompanyHistoricalData(
        string $companyName,
        \DateTime $startDate,
        \DateTime $endDate,
        ?string $region = null
    ): array {
        $data = $this->financeApiClient->getHistoricalData($companyName, $region);

        return $data;
    }
}
