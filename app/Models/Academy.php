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

    public static function getUnitNameByUnitNo($unit_no)
    {
        if ($unit_no === 0) return 'Admin';

        $academy = self::where('Academy_No', $unit_no)->first();
        return $academy->Academy_Name;
    }
}
