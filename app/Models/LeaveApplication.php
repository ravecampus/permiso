<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    use HasFactory;

    protected $table = "leave_application";
    protected $fillable = [
        'user_id',
        'leave_id',
        'leave_credit_id',
        'initial_appr_id',
        'initial_appr_date',
        'final_appr_id',
        'final_appr_date',
        'school_year_id',
        'emp_class_id',
        'leave_credit_id',
        'date_apply',
        'from',
        'from_ext',
        'until',
        'until_ext',
        'number_of_day',
        'cause',
        'remarks',
        'status',
        'sick_attach',
        'sa_size',
        'sa_type',
        'sa_orig_name',

    ];


    public function schoolyear(){
        return $this->hasOne(SchoolYear::class,'id', 'school_year_id');
    }

    public function initial(){
        return $this->hasOne(User::class,'id', 'initial_appr_id');
    }
    public function final(){
        return $this->hasOne(User::class,'id', 'final_appr_id');
    }

    public function signature(){
        return $this->hasOne(User::class,'id', 'user_id')->with("position", "office");
    }


}