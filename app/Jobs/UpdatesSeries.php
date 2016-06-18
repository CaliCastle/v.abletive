<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Series;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class UpdatesSeries extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $series;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Series $series)
    {
        $this->series = $series;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Send email queue
        $this->sendEmail();
    }

    /**
     * Send email to its subscribers
     * 
     * @return $this
     */
    private function sendEmail()
    {
        $lesson = $this->series->lessons->reverse()->first();

        foreach ($this->series->subscriber as $user) {
            if ($user->subscribed() && !is_null($user->email) && $user->email != "") {
                Mail::queue('emails.update', ['user' => $user, 'series' => $this->series, 'lesson' => $lesson], function ($m) use ($user) {
                    $m->from('cali@calicastle.com', config('app.site.title'));
                    $m->to($user->email)->subject('您订阅的课程更新啦!');
                });
            }
        }

        $message = sprintf(
            '<%s|《%s》>系列现在更新啦, 时长%s的新集:<%s|【%s】>',
            $this->series->link(),
            $this->series->title,
            $lesson->duration,
            $lesson->link(),
            $lesson->title
        );

        Slack::to('#video')
            ->attach([
                'fallback'    => $message,
                'author_name' => '@' . $lesson->user->display_name,
                'author_link' => $lesson->user->profileLink(),
                'fields'      => [
                    [
                        'title' => '：',
                        'value' => $lesson->description,
                        'short' => true
                    ]
                ]
            ])
            ->send($message);

        return $this;
    }
}
