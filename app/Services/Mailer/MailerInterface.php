<?php

declare(strict_types=1);

namespace App\Services\Mailer;

interface MailerInterface
{
    /**
     * @throws NoSuchRecipientException
     * @throws ServerTemporarilyNotAvailableException
     */
    public function send(string $recipientEmail, string $subject, string $body): void;
}
