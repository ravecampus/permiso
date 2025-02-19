<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InitialApproval extends Model
{
    use HasFactory;

    protected $table = "initial_approvals";
    protected $fillable = [
        'user_id', 
        'name', 
        'emp_class_id'
    ];
}
