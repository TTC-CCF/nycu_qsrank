<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employer_list;
use App\Models\EmployerYearResult;

class EmployerYearResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $years = Employer_list::select("SN", "year")->get();
        foreach($years as $year) {
            EmployerYearResult::create([
                "employer_id" => $year->SN,
                "year" => $year->year,
                "result" => rand(0, 1),
            ]);
        }
    }
}
