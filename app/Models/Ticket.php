<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 
use App\Models\TicketComment;


class Ticket extends Model
{
    use HasFactory;
   // ✅ Thêm dòng này:
   protected $fillable = ['title', 'description', 'assigned_to', 'created_by'];

    // Mối quan hệ với người tạo ticket (creator)
    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Mối quan hệ với người được giao ticket (assignedUser)
    public function assignedUser() {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Mối quan hệ với các comment của ticket
    public function comments() {
        return $this->hasMany(TicketComment::class);
    }
}
