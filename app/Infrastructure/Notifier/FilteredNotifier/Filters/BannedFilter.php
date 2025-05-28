<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifier\FilteredNotifier\Filters;

use App\Infrastructure\Notifier\FilteredNotifier\FilterInterface;

class BannedFilter implements FilterInterface
{
    public const BANNED = [2, 3];

    public function filter(string $text, int $recipientId): bool
    {
        if (in_array($recipientId, self::BANNED, true)) {
            return false; // Do not send message to banned users
        }

        return true; // Send message to non-banned users
    }
}
