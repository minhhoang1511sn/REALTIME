<?php 

namespace App\Events;

use App\Models\TicketComment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentReadEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;

    public function __construct(TicketComment $comment)
    {
        $this->comment = $comment;
    }

    public function broadcastOn()
    {
        return new Channel('ticket.' . $this->comment->ticket_id);
    }

    public function broadcastWith()
    {
        return [
            'comment' => [
                'id' => $this->comment->id,
                'ticket_id' => $this->comment->ticket_id,
                'content' => $this->comment->content,
                'user_id' => $this->comment->user_id,
                'is_read' => $this->comment->is_read,
                'created_at' => $this->comment->created_at->toISOString(),
            ]
        ];
    }

    public function broadcastAs()
    {
        return 'comment.read';
    }
}
