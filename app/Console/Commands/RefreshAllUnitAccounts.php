<?php

namespace App\Console\Commands;

use App\Models\Academy;
use Exception;
use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RefreshAllUnitAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:addall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add all units account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::where('unit', '!=', 'Cirda')->delete();
        $academy_list = Academy::pluck('Academy_Name');

        try {
            $sn = User::max('sn') + 1;

            foreach ($academy_list as $academy) {
                $pwd = $this->randomPassword();
                $password = Hash::make($pwd);
                $this->info($academy .' ('. implode(', ', $this->unitEmailsDict[$academy]).') password：'. $pwd);
                foreach ($this->unitEmailsDict[$academy] as $email) {
                    $user = new User;
                    $user->SN = $sn;
                    $user->account = $email;
                    $user->email = $email;
                    $user->password = $password;
                    $user->unit = $academy;
                    $user->unitno = substr($academy, 0, 2);
                    $user->save();
                    $sn++;
                }
            }
            
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollback();
            $this->error($e->getMessage());
        }
        
    }

    private $unitEmailsDict = [
        '01人文社會學院' => ['wjwang@nycu.edu.tw', 'jwchang@nycu.edu.tw', 'chjean@nycu.edu.tw'],
        '02人文與社會科學院' => ['wjwang@nycu.edu.tw', 'chenyaya@nycu.edu.tw'],
        '03工學院' => ['cplin.ce@nycu.edu.tw', 'lcpeng@nycu.edu.tw', 'daphnechang@nycu.edu.tw'],
        '04牙醫學院' => ['sykao@nycu.edu.tw', 'wh65255@nycu.edu.tw'],
        '05生命科學院' => ['cclien@nycu.edu.tw', 'yfyang@nycu.edu.tw', 'pchsiao2@nycu.edu.tw'],
        '06生物科技學院'=> ['moon@nycu.edu.tw', 'sunny@nycu.edu.tw'],
        '07生物醫學暨工程學院' => ['cllin2@nycu.edu.tw', 'wshsueh@nycu.edu.tw'],
        '08光電學院' => ['cwlu@nycu.edu.tw', 'lingching@nycu.edu.tw', 'yuching@nycu.edu.tw'],
        '09客家文化學院' => ['mlchien@nycu.edu.tw', 'maihua@nycu.edu.tw'],
        '10科技法律學院' => ['chen@nycu.edu.tw', 'yuxuan@nycu.edu.tw', 'law@nycu.edu.tw'],
        '12產學創新學院' => ['jycsun@nycu.edu.tw', 'elsa@mail.nctu.edu.tw', 'iais@nycu.edu.tw'],
        '13理學院' => ['mclai@nycu.edu.tw', 'joanliu@nycu.edu.tw', 'page54@nycu.edu.tw'],
        '14國際半導體學院' => ['edc@nycu.edu.tw', 'ting0322@nycu.edu.tw', 'hssun@nycu.edu.tw'],
        '15智慧科學暨綠能學院' => ['rhhwang@nycu.edu.tw', 'tadashi0504@nycu.edu.tw', 'yljen@nycu.edu.tw'],
        '16電機學院' => ['dean.ece@nycu.edu.tw', 'krystal@nycu.edu.tw', 'yuyen@nycu.edu.tw'],
        '17資訊學院'=> ['jcc@nycu.edu.tw', 'chiachang622@cs.nycu.edu.tw'],
        '18管理學院' => ['chunghuimin8@nycu.edu.tw', 'jessiechou@nycu.edu.tw', 'tsmi@nycu.edu.tw'],
        '19醫學院' => ['chchen3@nycu.edu.tw', 'yunsyuan@nycu.edu.tw', 'ycyeh@nycu.edu.tw'],
        '20藥物科學院' => ['myalin@nycu.edu.tw', 'footababy@nycu.edu.tw'],
        '21護理學院' => ['hsiuju26@nycu.edu.tw', 'yingj26@nycu.edu.tw', 'shh@nycu.edu.tw'],

    ];
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
