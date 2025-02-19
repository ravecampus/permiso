<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmploymentClassification extends Model
{
    use HasFactory;

    protected $table = "employment_classification";

    protected $fillable = ['title', 'sub_title'];
}
