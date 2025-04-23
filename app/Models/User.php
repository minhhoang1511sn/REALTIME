<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Ticket;
use App\Models\TicketComment;


class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use Notifiable;

    /**
     * Các thuộc tính có thể gán qua phương thức mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'name',      // Thêm 'name' vào đây
        'email',
        'password',
    ];

    /**
     * Các thuộc tính không được phép gán qua phương thức mass assignment.
     *
     * @var array
     */
    // protected $guarded = [];  // Nếu bạn muốn bảo vệ toàn bộ thuộc tính
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_user_id');
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }
}
