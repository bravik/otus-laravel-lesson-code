<?php

declare(strict_types=1);

namespace App\Services\Mailer;

use Psr\Log\LoggerInterface;

class Mailer implements MailerInterface
{
    public function __construct(
        private  LoggerInterface $logger
    ) {
    }

    public function send(string $recipientEmail, string $subject, string $body): void
    {
        sleep(5); // имитируем задержку отправки email
        $this->logger->info('Email sent', [
            'recipientEmail' => $recipientEmail,
            'subject' => $subject,
            'body' => $body,
        ]);
    }
}
