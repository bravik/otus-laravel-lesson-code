<?php

declare(strict_types=1);

namespace App\Notifications;

class Notifier
{
    public function send(string $message): void
    {
        echo $message;
    }
}
