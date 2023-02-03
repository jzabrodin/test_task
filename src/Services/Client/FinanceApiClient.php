<?php

declare(strict_types=1);

namespace App\Services\Client;

interface FinanceApiClient
{
    public function getHistoricalData(string $companyName, ?string $region): array;
}
