<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'school_id',
        'position_id',
        'office_id',
        'emp_class_id',
        'role',
        'date_of_employment',
        'email',
        'password',
        'image',
        'img_size',
        'img_type',
        'img_orig_name',
        'digital_signature',
        'ds_size',
        'ds_type',
        'ds_orig_name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function position(){
        return $this->hasOne(Position::class,'id', 'position_id');
    }

    public function office(){
        return $this->hasOne(Office::class,'id', 'office_id');
    }
    public function emp_class(){
        return $this->hasOne(EmploymentClassification::class,'id', 'emp_class_id');
    }

    public function empleave_credit(){
        return $this->hasMany(LevelLeavecredit::class,'emp_class_id', 'emp_class_id');
    }


}
