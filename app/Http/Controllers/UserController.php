<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Academy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public $output;
    function __construct() {
        $this->output = new \Symfony\Component\Console\Output\ConsoleOutput();

      }
    public function showUnits() {
        $units = User::all();
        $academy_list = Academy::pluck('Academy_Name');
        return view('unitsManager', ['academy_list' => $academy_list, 'units' => $units, 'admin' => true, 'add_status' => '']);
    }
    public function addAccount(Request $request) {
        $body = $request->getContent();
        $body = json_decode($body);
        $unit = $body->unit;
        $account = $body->account;

        DB::beginTransaction();
        try{
            $pwd = User::where('unit', $unit)->first();
            $max_sn = User::query()->max('SN') + 1;
            $isNewPwd = false;
            if ($pwd){
                $pwd = $pwd->password;
            } else {
                $pwd = $this->randomPassword();
                $isNewPwd = true;
            }
            $user = new User;
            $user->SN = $max_sn;
            $user->account = $account;
            $user->password = $pwd;
            $user->email = $account;
            $user->unit = $unit;
            $user->unitno = substr($unit, 0, 2);
            $user->permission_mode = 'read';
            $user->save();

            DB::commit();
            if ($isNewPwd) {
                return response()->json([
                    'status' => 'success',
                    'msg' => '新增成功, 密碼為：'.$pwd,
                    'newAccount' => $account,
                    'SN' => $max_sn,
                ]);
            } else {
                return response()->json([
                    'status' => 'success',
                    'msg' => '新增成功',
                    'newAccount' => $account,
                    'SN' => $max_sn,
                ]);
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->output->writeln($e->getMessage());
            return response()->json(['status' => 'failed', 'msg' => '新增失敗']);
        }

        
    }
    public function deleteAccount(Request $request) {
        $body = $request->getContent();
        $body = json_decode($body);

        $SN = $body->SN;

        DB::beginTransaction();
        try {
            User::where('SN', $SN)->delete();
            DB::commit();
            return response()->json(['status' => 'success', 'msg' => '刪除成功']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->output->writeln($e->getMessage());
            return response()->json(['status' => 'failed', 'msg' => '刪除失敗']);
        }
        

    }
    public function editAccount(Request $request) {
        $body = $request->getContent();
        $body = json_decode($body);
        $account = $body->account;
        $sn = $body->SN;

        DB::beginTransaction();
        try{
            User::where('SN', $sn)->update(['account' => $account, 'email' => $account]);
            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'msg' => '修改成功',
                'newAccount' => $account,
                'SN' => $sn,
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->output->writeln($e->getMessage());
            return response()->json(['status' => 'failed', 'msg' => '新增失敗']);
        }
    }
    public function changeAccountPassword(Request $request) {
        try {
            $pwd = $request->input('password');
            $unit = $request->input('unit');

            User::where('unit', $unit)->update(['password' => Hash::make($pwd)]);
            return response()->json(['status' => 'success', 'msg' => '更改成功', 'password' => $pwd]);

        } catch (\Exception $e) {
            $this->output->writeln($e->getMessage());
            return response()->json(['status' => 'failed', 'msg' => '發生錯誤']);
        }

    }

    private function randomPassword($len = 8) {

        //enforce min length 8
        if($len < 8)
            $len = 8;
    
        //define character libraries - remove ambiguous characters like iIl|1 0oO
        $sets = array();
        $sets[] = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        $sets[] = '23456789';
        $sets[]  = '~!@#$%^&*(){}[],./?';
    
        $password = '';
        
        //append a character from each set - gets first 4 characters
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
        }
    
        //use all characters to fill up to $len
        while(strlen($password) < $len) {
            //get a random set
            $randomSet = $sets[array_rand($sets)];
            
            //add a random char from the random set
            $password .= $randomSet[array_rand(str_split($randomSet))]; 
        }
        
        //shuffle the password string before returning!
        return str_shuffle($password);
    }
}
