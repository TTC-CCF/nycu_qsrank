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
        Schema::create('employer_year_result', function (Blueprint $table) {
            $table->id();
            $table->integer("employer_id");
            $table->integer("year");
            $table->boolean("result");
            $table->timestamps();
            $table->foreign('employer_id')
            ->references('SN')->on('Employer_list')
            ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employer_year_result');
    }
};
