<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalApproval extends Model
{
    use HasFactory;

    protected $table = "final_approvals";

    protected $fillable = ["user_id", "emp_class_id", "name"];
    

}
