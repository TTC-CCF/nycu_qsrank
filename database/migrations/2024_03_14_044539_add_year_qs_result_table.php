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
        Schema::create('scholar_year_result', function (Blueprint $table) {
            $table->id();
            $table->integer("scholar_id");
            $table->integer("year");
            $table->boolean("result");
            $table->timestamps();
            $table->foreign('scholar_id')
            ->references('SN')->on('Scholar_list')
            ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholar_year_result');
    }
};
