<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployerYearResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $years = DB::table("Employer_list")->select("SN", "year")->get();
        foreach($years as $year) {
            DB::table("employer_year_result")->insert([
                "employer_id" => $year->SN,
                "year" => $year->year,
                "result" => rand(0, 1),
            ]);
        }
    }
}
