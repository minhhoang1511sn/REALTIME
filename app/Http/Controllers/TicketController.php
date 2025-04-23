<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\User;
use App\Events\CommentCreated;
use App\Events\CommentReadEvent;
use App\Models\TicketComment;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('assigned_to', Auth::id())->orWhere('created_by', Auth::id())->get();
        return view('tickets.index', compact('tickets'));
    }
    public function create()
    {
        // Lấy tất cả người dùng từ cơ sở dữ liệu
        $users = User::all();

        // Trả về view và truyền biến users
        return view('tickets.create', compact('users'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'required|exists:users,id',
        ]);

        Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('tickets.index')->with('success', 'Ticket created.');
    }

    public function show(Ticket $ticket)
    {
        // Kiểm tra quyền truy cập của người dùng
        if (Auth::id() !== $ticket->created_by && Auth::id() !== $ticket->assigned_to) {
            abort(403);
        }

        if (Auth::id() === $ticket->assigned_to) {
            $ticket->comments()
                ->where('is_read', false)
                ->where('user_id', '!=', Auth::id()) // không đánh dấu đã đọc cho comment của chính mình
                ->update(['is_read' => true]);
        }
        // Truy vấn với eager load các mối quan hệ
        $ticket = Ticket::with(['creator', 'assignedUser', 'comments.user'])->findOrFail($ticket->id);


        return view('tickets.show', compact('ticket'));
    }
    public function markAsRead(Ticket $ticket)
    {
        if (in_array(Auth::id(), [$ticket->created_by, $ticket->assigned_to])) {
            // Lấy các comment chưa đọc của người khác
            $comments = $ticket->comments()
                ->where('is_read', false)
                ->where('user_id', '!=', Auth::id())
                ->get();
    
            // Cập nhật is_read và broadcast sự kiện
            foreach ($comments as $comment) {
                // Đánh dấu là đã đọc
                $comment->update(['is_read' => true]);
    
                // Broadcast sự kiện cho tất cả những người liên quan
                broadcast(new CommentReadEvent($comment));
            }
        }
    
        return response()->json(['status' => 'ok']);
    }
    

    public function commentRead(TicketComment $comment)
    {
        // Cập nhật trạng thái is_read cho comment khi người nhận xem
        $comment->update(['is_read' => true]);

        // Broadcast sự kiện
        broadcast(new CommentReadEvent($comment));

        return response()->json(['status' => 'ok']);
    }
    
    
    public function addComment(Request $request, Ticket $ticket)
    {
        $comment = $ticket->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->input('message'),
            'is_read' => $ticket->assigned_to == Auth::id() ? true : false,
        ]);

        broadcast(new CommentCreated($comment));

        return back();
    }
}
