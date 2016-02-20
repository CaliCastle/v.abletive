<?php

namespace App\Listeners;

use App\Events\CommentReplyEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewReply
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CommentReplyEvent  $event
     * @return void
     */
    public function handle(CommentReplyEvent $event)
    {
        //
    }
}
