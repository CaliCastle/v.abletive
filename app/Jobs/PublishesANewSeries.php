<?php

namespace App\Jobs;

use Slack;
use App\Series;
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
        if (! $series)
            return redirect()->back()->withInput($request->all());
        $series->skills()->attach($request->input('skills'));

        $this->series = $series;

        $this->sendSlack();
    }

    /**
     * Send slack notification.
     *
     * @return $this
     */
    private function sendSlack()
    {
        $message = sprintf(
            '新的课程 <%s|《%s》>发布了, %s难度。 <%s|封面图>',
            $this->series->link(),
            $this->series->title,
            trans('lessons.difficulty.' . strtolower($this->series->difficulty)),
            $this->series->thumbnail
        );

        Slack::to('#general')
            ->attach([
                'fallback'    => $message,
                'author_name' => '所属技能: ' . trans('skills.' . $this->series->skills()->first()->name),
                'fields'      => [
                    [
                        'title' => '课程描述：',
                        'value' => $this->series->description,
                        'short' => true
                    ]
                ]
            ])
            ->send($message);

        return $this;
    }
}
