<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\CompanyInfoDTO;

class CompanyInfoDTOFactory
{
    public function create(
        string $startDate,
        string $endDate,
        string $companySymbol,
        string $email,
        ?string $region = null
    ): CompanyInfoDTO {
        return (new CompanyInfoDTO())
            ->setCompanySymbol($companySymbol)
            ->setEmail($email)
            ->setRegion($region)
            ->setStartDate(\DateTime::createFromFormat('Y-m-d', $startDate))
            ->setEndDate(\DateTime::createFromFormat('Y-m-d', $endDate));
    }
}
