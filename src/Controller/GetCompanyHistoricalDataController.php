<?php

declare(strict_types=1);

namespace App\Controller;

use App\Factory\CompanyInfoDTOFactory;
use App\Message\CompanyHistoricalDataEmailMessage;
use App\Services\GetCompanyHistoricalDataByStartAndEndDateInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GetCompanyHistoricalDataController extends AbstractController
{
    private GetCompanyHistoricalDataByStartAndEndDateInterface $companyService;
    private MessageBusInterface $messageBus;
    private LoggerInterface $logger;
    private CompanyInfoDTOFactory $companyInfoDTOFactory;
    private ValidatorInterface $validator;

    public function __construct(
        GetCompanyHistoricalDataByStartAndEndDateInterface $companyService,
        MessageBusInterface $messageBus,
        LoggerInterface $logger,
        CompanyInfoDTOFactory $companyInfoDTOFactory,
        ValidatorInterface $validator
    ) {
        $this->companyService = $companyService;
        $this->messageBus = $messageBus;
        $this->logger = $logger;
        $this->companyInfoDTOFactory = $companyInfoDTOFactory;
        $this->validator = $validator;
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
    public function processCompanyInfo(Request $request): Response
    {
        $this->logger->info(
            'processCompanyInfo'
        );

        $queryParameters = $request->query->all();
        $companyInfoDTO = $this->companyInfoDTOFactory->create(
            $queryParameters['startDate'],
            $queryParameters['endDate'],
            $queryParameters['companySymbol'],
            $queryParameters['email'],
            $queryParameters['region'] ?? null,
        );

        $violationsList = $this->validator->validate($companyInfoDTO);

        if ($violationsList->count()) {
            return $this->json(
                $violationsList,
                500
            );
        }

        $companyInfo = $this->companyService->getFilteredCompanyHistoricalData($companyInfoDTO);

        $this->logger->info('email message dispatched');

        $this->messageBus->dispatch(
            new CompanyHistoricalDataEmailMessage(
                $companyInfoDTO->getCompanySymbol(),
                $companyInfoDTO->getEmail(),
                $companyInfoDTO->getStartDate()->format('Y-m-d'),
                $companyInfoDTO->getEndDate()->format('Y-m-d')
            )
        );

        return $this->json($companyInfo);
    }
}
