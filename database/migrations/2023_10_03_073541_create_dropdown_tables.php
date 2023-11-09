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
        Schema::create('Industry', function (Blueprint $table) {
            $table->string('name');
        });

        Schema::create('Country', function (Blueprint $table) {
            $table->string('name');
        });

        Schema::create('Title', function (Blueprint $table) {
            $table->string('name');
            $table->string('belongs_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Industry');
        Schema::dropIfExists('Title');
        Schema::dropIfExists('Country');
    }
};
