<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;





Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth')->group(function () {
    // Route hiển thị danh sách tickets
    Route::get('/', [TicketController::class, 'index'])->name('tickets.index');
    
    // Route hiển thị form tạo mới ticket
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    // Route để xem chi tiết ticket
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');

    Route::post('/tickets/{ticket}/mark-read', [TicketController::class, 'markAsRead'])->name('tickets.markRead');
    // Route::post('/api/comments/{comment}/read', function (\App\Models\TicketComment $comment) {
    //     if ($comment->user_id !== Auth::id()) {
    //         $comment->update(['is_read' => true]);
    //     }
    //     return response()->noContent();
    // })->middleware('auth');

    Route::post('/api/comments/{comment}/read', [TicketController::class, 'commentRead']);
    // Route lưu ticket mới
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');

    // Route thêm comment vào ticket
    Route::post('/tickets/{ticket}/comments', [TicketController::class, 'addComment'])->name('tickets.comment');
});

Broadcast::routes([
    'middleware' => ['web','auth']
]);
Route::post('/broadcasting/auth', function () {
    return Broadcast::auth(request());
})->middleware(['web', 'auth']);