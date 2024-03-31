<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarYearResult extends Model
{
    use HasFactory;
    protected $table = 'scholar_year_result';
    protected $fillable = array('*');
    
}
