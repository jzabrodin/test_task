<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\GetCompaniesInfoInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetCompanyInfoController extends AbstractController
{
    private GetCompaniesInfoInterface $getCompaniesInfoService;
    private LoggerInterface $logger;

    public function __construct(
        GetCompaniesInfoInterface $getCompaniesInfoService,
        LoggerInterface $logger
    )
    {
        $this->getCompaniesInfoService = $getCompaniesInfoService;
        $this->logger = $logger;
    }

    #[Route('/get_company_to_name_map', name: 'app_get_company_info_to_name_map')]
    public function index(): Response
    {
        $this->logger->info('Get companies info');

        return $this->json(
            $this->getCompaniesInfoService->getCompanyAbbreviationToNameMap()
        );
    }
}
