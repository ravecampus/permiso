<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = "notifications";
    protected $fillable = [
        "sender_id",
        "receiver_id",
        "leave_application_id",
        "description",
        "message",
        "ref_number",
        "status",
    ];

    public function sender(){
        return $this->hasOne(User::class,'id', 'sender_id');
    }

    
    public function leaveapp(){
        return $this->hasOne(LeaveApplication::class,'id', 'leave_application_id');
    }
}