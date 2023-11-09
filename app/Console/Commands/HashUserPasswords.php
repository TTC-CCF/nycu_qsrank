<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class HashUserPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hash:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hash passwords for all users';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $users = User::all();

        foreach ($users as $user) {
            $user->password = Hash::make($user->password);

            // check errors
            if (!$user->save()) {
                $errors = $user->getErrors();
                $output->writeln($errors);
            }
        }

        // check update result
        $users = User::all();
        foreach ($users as $user) {
            $output->writeln($user->password);
        }

        $this->info('Passwords for all users have been hashed.');
    }
}
