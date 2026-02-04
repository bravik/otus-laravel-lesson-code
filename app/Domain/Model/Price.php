<?php

declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Events\PriceChangedEvent;

class Price
{
    use EventsTrait;

    private string $amount;
    private string $currency;

    public function __construct(string $amount, string $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }


    public function change(string $amount): void
    {
        $this->amount = $amount;

        $this->recordEvent(
            new PriceChangedEvent()
        );
    }
}
