<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'account',
        'email',
        'password',
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
        'password' => 'hashed',
    ];

    public function isAdmin()
    {
        return $this->unitno == 0;
    }


    protected $primaryKey = 'SN';
    public $timestamps = false;

    public static function addAccount($unit, $account)
    {

        DB::beginTransaction();
        try {
            if (self::where('account', $account)->exists()) {
                throw new \Exception('Account exists');
            }

            $pwd = self::where('unit', $unit)->first();
            $max_sn = self::max('SN') + 1;
            $isNewPwd = false;
            if ($pwd) {
                $pwd = $pwd->password;
            } else {
                $pwd = self::randomPassword();
                $isNewPwd = true;
            }
            $user = new User;
            $user->SN = $max_sn;
            $user->account = $account;
            $user->password = $pwd;
            $user->email = $account;
            $user->unit = $unit;
            $user->unitno = substr($unit, 0, 2);
            $user->save();

            DB::commit();
            return [$isNewPwd, $pwd, $max_sn];

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function deleteAccount($sn)
    {
        DB::beginTransaction();
        try {
            self::where('SN', $sn)->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function editAccount($sn, $account)
    {
        DB::beginTransaction();
        try {
            self::where('SN', $sn)->update(['account' => $account, 'email' => $account]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function changeAccountPassword($unit, $pwd)
    {
        DB::beginTransaction();
        try {
            User::where('unit', $unit)->update(['password' => $pwd]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    private static function randomPassword($len = 8)
    {

        //enforce min length 8
        if ($len < 8)
            $len = 8;

        //define character libraries - remove ambiguous characters like iIl|1 0oO
        $sets = array();
        $sets[] = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        $sets[] = '23456789';
        $sets[] = '~!@#$%^&*(){}[],./?';

        $password = '';

        //append a character from each set - gets first 4 characters
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
        }

        //use all characters to fill up to $len
        while (strlen($password) < $len) {
            //get a random set
            $randomSet = $sets[array_rand($sets)];

            //add a random char from the random set
            $password .= $randomSet[array_rand(str_split($randomSet))];
        }

        //shuffle the password string before returning!
        return str_shuffle($password);
    }
}
