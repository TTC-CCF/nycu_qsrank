<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholar_list extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Scholar_list';
    protected $primaryKey = 'SN';
    protected $fillable = array('*');
    public $timestamps = false;
}
