<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Client\NasdaqCompaniesInfoClient;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GetCompaniesInfoService implements GetCompaniesInfoInterface
{
    private NasdaqCompaniesInfoClient $companiesInfoClient;
    private LoggerInterface $logger;
    private CacheInterface $cache;

    public function __construct(
        NasdaqCompaniesInfoClient $companiesInfoClient,
        CacheInterface $cache,
        LoggerInterface $logger
    ) {
        $this->companiesInfoClient = $companiesInfoClient;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    private function getCompaniesInfoArray(): array
    {
        $response = $this->companiesInfoClient->getNasdaqListedCompaniesResponse();

        try {
            $responseAsArray = $response === null ? [] : $response->toArray();
        } catch (\Exception|TransportExceptionInterface|DecodingExceptionInterface $e) {
            $this->logger->error(
                self::class . '::getCompaniesInfo' . $e::class,
                [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]
            );

            $responseAsArray = [];
        }

        return $responseAsArray;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getCompaniesInfo(): array
    {
        $key = 'nasdaq-companies-info';

        return $this->cache->get(
            $key,
            function (ItemInterface $item) {
                $item->expiresAfter(86400);
                return $this->getCompaniesInfoArray();
            }
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getCompanyAbbreviationToNameMap(): array
    {
        $key = 'nasdaq-companies-info-map';

        return $this->cache->get(
            $key,
            function (ItemInterface $item) {
                $item->expiresAfter(86400);

                $companiesInfoArray = $this->getCompaniesInfoArray();
                $abbreviationToNameMap = [];
                foreach ($companiesInfoArray as $companyInfoRow) {
                    $abbreviation = $companyInfoRow['Symbol'];
                    $abbreviationToNameMap[$abbreviation] = $companyInfoRow['Company Name'];
                }

                return $abbreviationToNameMap;
            }
        );
    }
}
