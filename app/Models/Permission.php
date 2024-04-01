<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permission';

    protected $fillable = array('*');

    public static function getPermission($unitno) 
    {
        return $unitno === 0 ? "write" : self::select("name")->where("unitno", $unitno)->get()[0]['name'];
    }

    public static function getAllPermissions() {
        $raw = self::all();

        foreach($raw as $row) {
            $permissions[$row->unitno] = $row->name;
        }

        return $permissions;
    }

    public static function updatePermissions($unitnos, $permits) {
        DB::beginTransaction();
        try {
            array_map(function ($unitno, $permit) {
                self::where('unitno', $unitno)->update(['name' => $permit]);
            }, $unitnos, $permits);
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }
}
