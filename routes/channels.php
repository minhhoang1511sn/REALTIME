<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;

Broadcast::channel('ticket.{ticket_id}', function ($user, $ticket_id) {
    return true;
    // Log::info('AUTH BROADCAST:', [
    //     'user' => $user->id,
    //     'ticket_id' => $ticket_id
    // ]);

    // $ticket = Ticket::find($ticket_id);
    // if (!$ticket) return false;

    // return $user->id === $ticket->created_by || $user->id === $ticket->assigned_to;
});

