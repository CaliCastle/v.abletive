<?php

namespace App\Listeners;

use App\Events\NewSeriesEvent;
use Illuminate\Foundation\Auth\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewSeriesAvailable
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewSeriesEvent  $event
     * @return void
     */
    public function handle(NewSeriesEvent $event)
    {

    }
}
