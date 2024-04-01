<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Academy;


class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unitnos = Academy::pluck("Academy_No");

        foreach ($unitnos as $unitno) {
            Permission::create([
                "unitno" => $unitno,
                "name" => "write",
            ]);
        }
    }
}
