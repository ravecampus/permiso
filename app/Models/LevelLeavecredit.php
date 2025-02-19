<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelLeavecredit extends Model
{
    use HasFactory;

    protected $table = "level_leavecredit";

    protected $fillable = [
        'emp_class_id', 
        'is_no_limit',
        'credits',
        'leave_id',
        'description'
    ];
}
