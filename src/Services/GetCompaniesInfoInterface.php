<?php

declare(strict_types=1);

namespace App\Services;

interface GetCompaniesInfoInterface
{
    public function getCompaniesInfo(): array;

    /**
     * @return array<string,string>
     */
    public function getCompanyAbbreviationToNameMap():array;
}