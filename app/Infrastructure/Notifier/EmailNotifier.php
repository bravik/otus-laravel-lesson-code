<?php

declare(strict_types=1);

namespace App\Infrastructure\Notifier;

use App\Services\NotifierInterface;
use App\Services\Repositories\UsersRepositoryInterface;
use Illuminate\Support\Facades\Mail;
use Webmozart\Assert\Assert;

/**
 * Смотри пояснения в
 * @see NotifierInterface
 */
class EmailNotifier implements NotifierInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
    ) {
    }

    public function send(string $text, int $recipientId): void
    {

        $user = $this->usersRepository->find($recipientId);
        Assert::notNull($user);

        $recipientEmail = $user->email;

        Mail::raw($text, static function ($message) use ($recipientEmail) {
            $message->to($recipientEmail)
                ->subject('Notification');
        });
    }
}
