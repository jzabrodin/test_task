<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\CompanyHistoricalDataEmailMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;

final class CompanyHistoricalDataEmailMessageHandler implements MessageHandlerInterface
{
    private MailerInterface $mailer;
    private LoggerInterface $logger;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public function __invoke(CompanyHistoricalDataEmailMessage $message)
    {
        try {
            $this->mailer->send(
                (new Email())
                    ->from('dont-reply@xm.com')
                    ->to($message->getEmail())
                    ->text(
                        'Hello! ' .
                        "\nYou worked with data for {$message->getCompanyName()} from {$message->getStartDate()} to {$message->getEndDate()}." .
                        'Have a good day!'
                    )
            );
        } catch (TransportExceptionInterface $e) {
            $this->logger->error(
                'Message didnt send',
                [
                    'exceptionMessage' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]
            );
        }
    }
}
