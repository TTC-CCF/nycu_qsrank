<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployerYearResult extends Model
{
    use HasFactory;

    protected $table = "employer_year_result";
    protected $fillable = array('*');
}
