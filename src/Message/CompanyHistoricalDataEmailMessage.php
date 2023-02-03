<?php

declare(strict_types=1);

namespace App\Message;

final class CompanyHistoricalDataEmailMessage
{
    private string $email;
    private string $startDate;
    private string $endDate;
    private string $companyName;

    /**
     * @param string $companyName
     * @param string $email
     * @param string $startDate
     * @param string $endDate
     */
    public function __construct(string $companyName, string $email, string $startDate, string $endDate)
    {
        $this->companyName = $companyName;
        $this->email = $email;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return string
     */
    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getStartDate(): string
    {
        return $this->startDate;
    }

    /**
     * @return string
     */
    public function getEndDate(): string
    {
        return $this->endDate;
    }
}
