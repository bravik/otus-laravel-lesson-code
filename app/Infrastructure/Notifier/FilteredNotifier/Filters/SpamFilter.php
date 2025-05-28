<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifier\FilteredNotifier\Filters;

use App\Infrastructure\Notifier\FilteredNotifier\FilterInterface;

class SpamFilter implements FilterInterface
{
 public const BANNED_KEYWORDS = [
        'spam',
        'scam',
        'fraud',
        'phishing',
        'malware',
    ];

    public function filter(string $text, int $recipientId): bool
    {
        foreach (self::BANNED_KEYWORDS as $keyword) {
            if (stripos($text, $keyword) !== false) {
                return false; // Spam detected
            }
        }

        return true; // No spam detected
    }
}
