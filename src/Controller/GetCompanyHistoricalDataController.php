<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\GetCompanyHistoricalDataByStartAndEndDateInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetCompanyHistoricalDataController extends AbstractController
{
    private GetCompanyHistoricalDataByStartAndEndDateInterface $companyService;

    public function __construct(GetCompanyHistoricalDataByStartAndEndDateInterface $companyService)
    {
        $this->companyService = $companyService;
    }

    #[Route('/', name: 'app_company_info')]
    public function index(): Response
    {
        return $this->render(
            'company_info/index.html.twig',
            [
                'controller_name' => 'GetCompanyHistoricalDataController',
            ]
        );
    }

    #[Route('/process_company_info', name: 'app_company_info_process')]
    public function processCompanyInfo(): Response
    {
        $dateTime = '';

        $companyInfo = $this->companyService->getCompanyHistoricalData(
            '',
            \DateTime::createFromFormat('Y-m-d', $dateTime),
            \DateTime::createFromFormat('Y-m-d', $dateTime)
        );

        return $this->render(
            'company_info/index.html.twig',
            [
                'controller_name' => 'GetCompanyHistoricalDataController',
            ]
        );
    }
}
