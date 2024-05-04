<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Academy;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public $output;
    function __construct()
    {
        $this->output = new \Symfony\Component\Console\Output\ConsoleOutput();

    }
    public function showUnits()
    {
        $units = User::all();
        $academy = Academy::all();
        $permit_each_unit = Permission::getAllPermissions();

        foreach ($academy as $row) {
            $academy_list[$row->Academy_No] = $row->Academy_Name;
        }
        return view(
            'unitsManager',
            [
                'academy_list' => $academy_list,
                'units' => $units,
                'admin' => true,
                'add_status' => '',
                'permit_each_unit' => $permit_each_unit
            ]
        );
    }
    public function addAccount(Request $request)
    {
        $body = $request->getContent();
        $body = json_decode($body);
        $unit = $body->unit;
        $account = $body->account;

        try {
            list($isNewPwd, $pwd, $max_sn) = User::addAccount($unit, $account);

            if ($isNewPwd) {
                return response()->json([
                    'status' => 'success',
                    'msg' => '新增成功, 密碼為：' . $pwd,
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
            $this->output->writeln($e->getMessage());
            return response()->json(['status' => 'failed', 'msg' => "新增失敗 {$e->getMessage()}"]);
        }


    }
    public function deleteAccount(Request $request)
    {
        $body = $request->getContent();
        $body = json_decode($body);

        $sn = $body->SN;

        try {
            User::deleteAccount($sn);
            return response()->json(['status' => 'success', 'msg' => '刪除成功']);
        } catch (\Exception $e) {
            $this->output->writeln($e->getMessage());
            return response()->json(['status' => 'failed', 'msg' => "刪除失敗 {$e->getMessage()}"]);
        }


    }
    public function editAccount(Request $request)
    {
        $body = $request->getContent();
        $body = json_decode($body);
        $account = $body->account;
        $sn = $body->SN;

        try {
            User::editAccount($sn, $account);

            return response()->json([
                'status' => 'success',
                'msg' => '修改成功',
                'newAccount' => $account,
                'SN' => $sn,
            ]);

        } catch (\Exception $e) {
            $this->output->writeln($e->getMessage());
            return response()->json(['status' => 'failed', 'msg' => "新增失敗 {$e->getMessage()}"]);
        }
    }
    public function changeAccountPassword(Request $request)
    {
        try {
            $pwd = $request->input('password');
            $unit = $request->input('unit');

            User::changeAccountPassword($unit, Hash::make($pwd));
            return response()->json(['status' => 'success', 'msg' => '更改成功', 'password' => $pwd]);

        } catch (\Exception $e) {
            $this->output->writeln($e->getMessage());
            return response()->json(['status' => 'failed', 'msg' => "發生錯誤 {$e->getMessage()}"]);
        }

    }

    public function changePermission(Request $request)
    {
        try {
            $formData = $request->all();
            foreach ($formData as $key => $data) {
                if ($data === "write" || $data === "readonly") {
                    $unitnos[] = $key;
                    $permits[] = $data;
                }
            }
            $unitnos = array_map(function ($key) {
                return explode('-', $key)[0];
            }, $unitnos);

            Permission::updatePermissions($unitnos, $permits);

            return response()->json(['status' => 'success', 'msg' => '更改成功']);
        } catch (\Exception $e) {
            $this->output->writeln($e->getMessage());
            return response()->json(['status' => 'failed', 'msg' => "發生錯誤 {$e->getMessage()}"]);
        }
    }
}
