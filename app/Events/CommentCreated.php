<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\TicketComment;
use Illuminate\Support\Facades\Log;

class CommentCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;

    public function __construct(TicketComment $comment)
    {
        $comment->load('user'); // 👈 Cần load user
        $this->comment = $comment;
    }

    public function broadcastOn()
    {   Log::info("da chay ne");
        return new Channel('ticket.' . $this->comment->ticket_id);
    }

    public function broadcastWith()
    {
        $data = [
            'id' => $this->comment->id,
            'ticket_id' => $this->comment->ticket_id,
            'content' => $this->comment->content,
            'user_id' => $this->comment->user_id,
            'is_read' => $this->comment->is_read,
            'user' => [
                'id' => $this->comment->user->id,
                'name' => $this->comment->user->name,
            ],
            'created_at' => $this->comment->created_at->toISOString(), // ISO dùng cho dayjs
        ];

        Log::info('Broadcasting comment.created event with data:', $data);

        return $data;
    }
    public function broadcastAs()
    {
        return 'comment.created';
    }
}
