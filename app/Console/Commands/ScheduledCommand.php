<?php

namespace App\Console\Commands;

use DateTimeInterface;
use Illuminate\Console\Command;

class ScheduledCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scheduled-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function handle(): void
    {
        sleep(2);
        $this->info((new \DateTimeImmutable())->format(DateTimeInterface::ISO8601_EXPANDED) . " Scheduled Command is here!");
    }
}
