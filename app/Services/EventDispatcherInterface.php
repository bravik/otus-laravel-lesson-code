<?php

declare(strict_types=1);

namespace App\Services;

use App\Domain\Events\DomainEventInterface;

interface EventDispatcherInterface
{
    public function disaptch(DomainEventInterface ...$events);
}
