<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\CompanyInfoDTO;

interface GetCompanyHistoricalDataByStartAndEndDateInterface
{
    public function getCompanyHistoricalData(CompanyInfoDTO $companyInfoDTO): array;
    public function getFilteredCompanyHistoricalData(CompanyInfoDTO $companyInfoDTO): array;
}
