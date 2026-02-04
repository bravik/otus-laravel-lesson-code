<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Events\DomainEventInterface;

trait EventsTrait
{
    public array $recordedEvents = [];

    public function recordEvent(DomainEventInterface $event): void
    {
        $this->recordedEvents[] = $event;
    }

    public function releaseEvents(): array
    {
        $events = $this->recordedEvents;
        $this->recordedEvents = [];
        return $events;
    }
}
