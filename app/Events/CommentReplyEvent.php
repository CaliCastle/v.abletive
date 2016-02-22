<?php

namespace App\Events;

use App\Events\Event;
use App\User;
use App\Video;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CommentReplyEvent extends Event
{
    use SerializesModels;

    public $sender;
    public $receiver;
    public $lesson;
    public $message;

    /**
     * Create a new event instance.
     *
     * @param User $sender
     * @param User $receiver
     * @param Video $lesson
     * @param $message
     */
    public function __construct(User $sender, User $receiver, Video $lesson, $message)
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->lesson = $lesson;
        $this->message = $message;
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
