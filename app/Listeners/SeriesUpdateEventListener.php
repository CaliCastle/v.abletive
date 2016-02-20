<?php

namespace App\Listeners;

use App\Events\SeriesUpdateEvent;
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
        $series = $event->series;
        $lesson = $series->lessons->reverse()->first();

        foreach ($series->subscriber as $user) {
            if ($user->subscribed()) {
                Mail::queue('emails.update', ['user' => $user, 'series' => $series, 'lesson' => $lesson], function ($m) use ($user) {
                    $m->from('cali@abletive.com', config('app.site.title'));
                    $m->to($user->email, $user->display_name)->subject('您订阅的课程更新啦!');
                });
            }
        }
    }
}
