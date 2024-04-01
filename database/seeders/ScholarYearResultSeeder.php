<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Scholar_list;
use App\Models\ScholarYearResult;

class ScholarYearResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $years = Scholar_list::select("SN", "year")->get();
        foreach($years as $year) {
            ScholarYearResult::create([
                "scholar_id" => $year->SN,
                "year" => $year->year,
                "result" => rand(0, 1),
            ]);
        }
        
    }
}
