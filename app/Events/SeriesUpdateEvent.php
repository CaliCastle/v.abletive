<?php

namespace App\Events;

use App\Events\Event;
use App\Series;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SeriesUpdateEvent extends Event
{
    public $series;

    /**
     * Create a new event instance.
     *
     * @param Series $series
     */
    public function __construct(Series $series)
    {
        $this->series = $series;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
