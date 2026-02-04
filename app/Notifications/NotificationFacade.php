<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void send(string $message)
 */
class NotificationFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Notifier::class;
    }
}
