<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table("Scholar_list", function (Blueprint $table) {
            $table->renameColumn('unit_name', 'unit_academy');
            $table->string('unit_department')->after('unit_name');
        });

        Schema::table("Employer_list", function (Blueprint $table) {
            $table->renameColumn('unit_name', 'unit_academy');
            $table->string('unit_department')->after('unit_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("Scholar_list", function (Blueprint $table) {
            $table->renameColumn('unit_academy', 'unit_name');
            $table->dropColumn('unit_department');
        });

        Schema::table("Employer_list", function (Blueprint $table) {
            $table->renameColumn('unit_academy', 'unit_name');
            $table->dropColumn('unit_department');
        });
    }
};
