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
        Schema::table("scholar_year_result", function (Blueprint $table) {
            $table->integer('result')->change();
        });

        Schema::table("employer_year_result", function (Blueprint $table) {
            $table->integer('result')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("scholar_year_result", function (Blueprint $table) {
            $table->boolean('result')->change();
        });

        Schema::table("employer_year_result", function (Blueprint $table) {
            $table->boolean('result')->change();
        });
    }
};
