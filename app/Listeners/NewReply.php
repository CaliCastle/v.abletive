<?php

namespace App\Listeners;

use App\User;
use Mail;
use Slack;
use App\Events\CommentReplyEvent;

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

        $sender = User::find($event->sender->id);

        $message = sprintf(
            '*%s* 在<%s|《%s》>中发表了评论',
            $sender->display_name,
            $event->lesson->link(),
            $event->lesson->title
        );

        Slack::to('#video')
            ->attach([
                'fallback'    => $message,
                'author_name' => '@' . $sender->display_name,
                'author_link' => $sender->profileLink(),
                'fields'      => [
                    [
                        'title' => '说道：',
                        'value' => $event->message,
                        'short' => true
                    ]
                ]
            ])
            ->withIcon($sender->avatar)
            ->send($message);
    }
}
