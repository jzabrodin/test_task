<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\HistoricalDataResponse;

interface GetCompanyHistoricalDataByStartAndEndDateInterface
{
    public function getCompanyHistoricalData(
        string $companyName,
        \DateTime $startDate,
        \DateTime $endDate,
        ?string $region = null
    ): array;
}