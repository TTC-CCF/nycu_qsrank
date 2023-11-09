<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Academy extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Academy';
    protected $primaryKey = 'SN';
    protected $fillable = array('*');
    public $timestamps = false;
}
