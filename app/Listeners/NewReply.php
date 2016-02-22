<?php

namespace App\Listeners;

use App\Events\CommentReplyEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class NewReply
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
     * @param  CommentReplyEvent  $event
     * @return void
     */
    public function handle(CommentReplyEvent $event)
    {
        if ($event->receiver->subscribed() && $event->receiver->email != "" && !is_null($event->receiver->email)) {
            Mail::queue('emails.new_comment', ['receiver' => $event->receiver, 'sender' => $event->sender, 'lesson' => $event->lesson, 'content' => $event->message], function ($m) use ($event) {
                $m->from('cali@calicastle.com', config('app.site.title'));
                $m->to($event->receiver->email)->subject($event->sender->display_name . '在《' . $event->lesson->title . '》中回复了您!');
            });
        }
    }
}
