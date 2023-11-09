<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AddNewUnitAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:add {unit} {unitno} {email*}';

    protected function promptForMissingArgumentsUsing()
    {
        return [
            'unit' => 'Which unit want to add account?',
            'unitno' => 'The unit number.',
            'email' => 'Give me email addresses of the account you want to add.',
        ];
    }
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add account to a unit linked with multiple emails with random password.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pwd = $this->randomPassword();
        $unit = $this->argument('unit');
        $unitno = $this->argument('unitno');
        $emails = $this->argument('email');

        try {
            DB::beginTransaction();
            $sn = User::max('sn') + 1;
            $password = Hash::make($pwd);

            foreach ($emails as $email) {
                $user = new User;
                $user->SN = $sn;
                $user->account = $email;
                $user->email = $email;
                $user->password = $password;
                $user->unit = $unit;
                $user->unitno = $unitno;
                $user->save();
                $sn++;
            }
            
            $academy_list = DB::table('Academy')->pluck('Academy_Name');
            if (in_array($unit, $academy_list->toArray())) {
                DB::table('Academy')->where('Academy_Name', $unit)->update(['Academy_No' => $unitno]);
            }
            else {
                DB::table('Academy')->insert(['Academy_Name' => $unit, 'Academy_No' => $unitno]);
            }
            
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollback();
            $this->error($e->getMessage());
        }
        $this->info('Added account to unit ' . $unit . ' with emails: ' . implode(', ', $emails) . ' and password: ' . $pwd);
        
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
