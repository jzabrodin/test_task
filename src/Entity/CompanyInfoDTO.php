<?php

declare(strict_types=1);

namespace App\Entity;

class CompanyInfoDTO
{
    private const DATE_FORMAT = 'Y-m-d';

    private string $companySymbol;
    private \DateTime $startDate;
    private \DateTime $endDate;
    private string $email;
    private ?string $region;

    /**
     * @return string
     */
    public function getCompanySymbol(): string
    {
        return $this->companySymbol;
    }

    /**
     * @param string $companySymbol
     * @return CompanyInfoDTO
     */
    public function setCompanySymbol(string $companySymbol): CompanyInfoDTO
    {
        $this->companySymbol = $companySymbol;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate
     * @return CompanyInfoDTO
     */
    public function setStartDate(\DateTime $startDate): CompanyInfoDTO
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate(): \DateTime
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime $endDate
     * @return CompanyInfoDTO
     */
    public function setEndDate(\DateTime $endDate): CompanyInfoDTO
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return CompanyInfoDTO
     */
    public function setEmail(string $email): CompanyInfoDTO
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * @param string|null $region
     * @return CompanyInfoDTO
     */
    public function setRegion(?string $region): CompanyInfoDTO
    {
        $this->region = $region;
        return $this;
    }

    public function __toString(): string
    {
        return sprintf(
            "Company symbol %s: \n start date: %s \n end date: %s \n email: %s",
            $this->companySymbol,
            $this->startDate->format(self::DATE_FORMAT),
            $this->endDate->format(self::DATE_FORMAT),
            $this->email
        );
    }
}