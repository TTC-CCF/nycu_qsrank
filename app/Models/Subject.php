<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Subject';
    protected $primaryKey = 'SN';
    protected $fillable = array('*');
    public $timestamps = false;

    public static function getSubjectList()
    {
        $subjects = self::all();
        $ms_dict = [];

        foreach ($subjects as $subject) {
            $broadSubjectArea = $subject->BroadSubjectArea;
            $mainSubject = $subject->MainSubject;

            if (!isset($ms_dict[$broadSubjectArea])) {
                $ms_dict[$broadSubjectArea] = [];
            }
            $ms_dict[$broadSubjectArea][] = $mainSubject;
        }

        return $ms_dict;
    }
}
