<?php

namespace App\Http\Controllers;

use App\Models\Academy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Psy\Readline\Hoa\Console;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public $output;
    function __construct() {
        $this->output = new \Symfony\Component\Console\Output\ConsoleOutput();
      }
    public function login(Request $request){
        try{
            $acc = $request->input('email');
            $psw = $request->input('password');
            $unit = $request->input('unitname');

            $users = User::where('account', $acc)->where('unit', $unit)->get();
            foreach ($users as $user) {
                if (Auth::attempt(['account' => $acc, 'password' => $psw, 'unit' => $unit])) {
                    Session::put('id', $user->unitno);
                    Session::put('list_mode', 'scholar');
                    return redirect()->route('list');
                }
            }
            return redirect()->route('login')->with('error', '登入失敗');

            
        } catch (\Exception $e){
            $this->output->writeln($e->getMessage());
            return redirect()->route('login')->with('error', '登入失敗');
        }
        
    }
    public function logout(){
        Session::remove('id');
        Session::remove('list_mode');
        Auth::logout();

        return redirect()->route('login');
    }
    public function showLoginForm(){
        // $this->output->writeln(Auth::user());
        $units = Academy::pluck('Academy_Name')->toArray();
        array_splice($units, 0, 0, ['Cirda']);
        if (Auth::check()){
            return redirect()->route('list');
        }
        return view('login', ['units' => $units]);
    }

}
