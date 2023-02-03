<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\CompanyInfoDTO;
use App\Services\Client\FinanceApiClient;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class GetCompanyHistoricalDataFromAPIService implements GetCompanyHistoricalDataByStartAndEndDateInterface
{
    private FinanceApiClient $financeApiClient;
    private CacheInterface $cache;

    public function __construct(
        FinanceApiClient $financeApiClient,
        CacheInterface $cache
    ) {
        $this->financeApiClient = $financeApiClient;
        $this->cache = $cache;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getFilteredCompanyHistoricalData(CompanyInfoDTO $companyInfoDTO): array
    {
        $historicalData = $this->getCompanyHistoricalData($companyInfoDTO);

        $filteredPrices = [];

        $startDateAsTimestamp = $companyInfoDTO->getStartDate()->getTimestamp();
        $endDateAsTimestamp = $companyInfoDTO->getEndDate()->getTimestamp();

        $historicalPrices = $historicalData['prices'] ?? [];
        foreach ($historicalPrices as $price) {
            $date = $price['date'];
            if ($date >= $startDateAsTimestamp && $date <= $endDateAsTimestamp) {
                $price['date'] = (new \DateTime())
                    ->setTimestamp($date)
                    ->format('Y-m-d');
                $filteredPrices[] = $price;
            }
        }

        return $filteredPrices;
    }

    /**
     * @param CompanyInfoDTO $companyInfoDTO
     * @return array
     * @throws InvalidArgumentException
     */
    public function getCompanyHistoricalData(CompanyInfoDTO $companyInfoDTO): array
    {
        $cacheKey = 'historical-data ' . $companyInfoDTO->getCompanySymbol() . $companyInfoDTO->getRegion() ?? '';
        return $this->cache->get(
            $cacheKey,
            function (ItemInterface $item) use ($companyInfoDTO) {
                $item->expiresAfter(86400);
                return $this->financeApiClient->getHistoricalData(
                    $companyInfoDTO->getCompanySymbol(),
                    $companyInfoDTO->getRegion()
                );
            }

        );
    }
}
