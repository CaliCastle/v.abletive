<?php

namespace App\Listeners;

use App\Events\SeriesUpdateEvent;
use App\Jobs\UpdatesSeries;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SeriesUpdateEventListener implements ShouldQueue
{
    public $mailer;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  SeriesUpdateEvent  $event
     * @return void
     */
    public function handle(SeriesUpdateEvent $event)
    {
        dispatch(new UpdatesSeries($event->series));
    }
}
