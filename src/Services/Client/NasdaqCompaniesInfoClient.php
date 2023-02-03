<?php

declare(strict_types=1);

namespace App\Services\Client;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface NasdaqCompaniesInfoClient
{
    public function getNasdaqListedCompaniesResponse(): ?ResponseInterface;
}
