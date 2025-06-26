<?php

namespace App\Jobs;

use App\Services\Mailer\MailerInterface;
use App\Services\Mailer\NoSuchRecipientException;
use App\Services\Mailer\ServerTemporarilyNotAvailableException;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendEmail implements ShouldQueue
{
    use Queueable, Batchable;

    public int $tries = 3; // Количество попыток выполнения задачи

    public function retryUntil()
    {
        return (new \DateTimeImmutable())->addMinutes(10); // Время, в течение которого задача будет повторяться
    }

    /**
     * Calculate the number of seconds to wait before retrying the job.
     *
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return [1, 5, 10];
    }

    /**
     * Все что передано в конструктор будет сериализовано и записано в БД.
     * Когда запустится обработка очереди, эти данные будут вытащены из БД, десериализованы и переданы в метод handle()
     */
    public function __construct(
        private string $recipientEmail,
        private string $subject,
        private string $body,
    ) {
    }

    /**
     * Execute the job.
     * В метод handle() можно внедрять зависимости из контейнера
     */
    public function handle(MailerInterface $mailer): void
    {
        try {
            $mailer->send($this->recipientEmail, $this->subject, $this->body);
        } catch (NoSuchRecipientException $e) {
            $this->fail($e);
        } catch (ServerTemporarilyNotAvailableException $e) {
            $this->release();
        }
    }

    public function failed($exception = null)
    {
    }
}
