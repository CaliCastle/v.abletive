<?php

namespace App\Jobs;

use App\User;
use App\Series;
use App\Jobs\Job;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\SeriesRequest;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PublishesANewSeries extends Job implements ShouldQueue {

    use InteractsWithQueue, SerializesModels;
    /**
     * @var Series
     */
    private $series;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @param SeriesRequest $request
     */
    public function handle(SeriesRequest $request)
    {
        $series = Series::create($request->all());
        if (!$series)
            return redirect()->back()->withInput($request->all());
        $series->skills()->attach($request->input('skills'));

        $this->series = $series;

        // Send email notifications
//        $this->sendEmail();
    }

    /**
     * Send email to its subscribers
     *
     * @return $this
     */
    private function sendEmail()
    {
        foreach (User::all() as $user) {
            if ($user->subscribed() && !is_null($user->email) && $user->email != "") {
                Mail::queue('emails.new_series', ['user' => $user, 'series' => $this->series], function ($m) use ($user) {
                    $m->from('cali@calicastle.com', config('app.site.title'));
                    $m->to($user->email)->subject('您订阅的课程更新啦!');
                });
            }
        }

        return $this;
    }
}
