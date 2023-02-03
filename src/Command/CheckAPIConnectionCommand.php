<?php

declare(strict_types=1);

namespace App\Command;

use App\Services\GetCompaniesInfoInterface;
use App\Services\GetCompanyHistoricalDataByStartAndEndDateInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'check:yf_api',
    description: 'Check connection to Yahoo Finance API',
)]
class CheckAPIConnectionCommand extends Command
{
    private GetCompanyHistoricalDataByStartAndEndDateInterface $getCompanyInfoByStartAndEndDateService;
    private GetCompaniesInfoInterface $getCompaniesInfoService;

    public function __construct(
        GetCompanyHistoricalDataByStartAndEndDateInterface $getCompanyInfoByStartAndEndDateService,
        GetCompaniesInfoInterface $getCompaniesInfoService
    ) {
        $this->getCompanyInfoByStartAndEndDateService = $getCompanyInfoByStartAndEndDateService;
        $this->getCompaniesInfoService = $getCompaniesInfoService;
        parent::__construct('check:yf_api');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $data = $this->getCompanyInfoByStartAndEndDateService->getCompanyHistoricalData(
            'COKE',
            \DateTime::createFromFormat('Y-m-d', '2022-02-02'),
            \DateTime::createFromFormat('Y-m-d', '2023-02-02')
        );

        if ($data->getPrices() !== []) {
            $io->success('getCompanyInfoByStartAndEndDateService::getCompanyHistoricalData - OK');
        }

        $companiesInfo = $this->getCompaniesInfoService->getCompaniesInfo();

        if($companiesInfo !== []){
            $io->success('getCompaniesInfoService::getCompaniesInfo - OK');
        }

        return Command::SUCCESS;
    }
}
