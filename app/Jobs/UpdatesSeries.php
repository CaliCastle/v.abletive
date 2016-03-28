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
        return $this;
    }
}
