<?php

namespace App\Base\Listeners;

use Exception;
use Illuminate\Queue\Events\QueueBusy;

class NotifyOfQueueBusy
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(QueueBusy $event): void
    {
        $notification = sprintf(
            "QueueBusy: The queue %s:%s went over its job limit. Current queued jobs: %d.",
            $event->connection,
            $event->queue,
            $event->size
        );

        report(new Exception($notification));
    }
}
