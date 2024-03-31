<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScholarYearResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $years = DB::table("Scholar_list")->select("SN", "year")->get();
        foreach($years as $year) {
            DB::table("scholar_year_result")->insert([
                "scholar_id" => $year->SN,
                "year" => $year->year,
                "result" => rand(0, 1),
            ]);
        }
        
    }
}
