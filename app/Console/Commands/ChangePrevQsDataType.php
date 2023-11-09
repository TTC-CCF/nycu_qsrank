<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ChangePrevQsDataType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:change-prev-qs-data-type';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change 去年是否同意參與QS datatype from var(5) to tinyint(1)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tables = ['Scholar_list', 'Employer_list'];
        foreach ($tables as $table) {
            $data = DB::table($table)->get();
            // iterate all $data
            foreach ($data as $row) {
                $prev_qs = $row->去年是否同意參與QS;
                if ($prev_qs == 'V') {
                    $prev_qs = 1;
                } else {
                    $prev_qs = 0;
                }
                DB::table($table)->where('SN', $row->SN)->update(['去年是否同意參與QS' => $prev_qs]);
            }
            DB::statement("ALTER TABLE `$table` MODIFY `去年是否同意參與QS` tinyint(1) NOT NULL DEFAULT 0");

        }
        
        
    }
}
