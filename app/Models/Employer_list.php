<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employer_list extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Employer_list';
    protected $primaryKey = 'SN';
    protected $fillable = array('*');
    public $timestamps = false;
}
