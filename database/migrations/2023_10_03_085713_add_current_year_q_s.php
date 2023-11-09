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
        Schema::table('Scholar_list', function (Blueprint $table) {
            $table->boolean('今年是否同意參與QS')->default(false);
        });
        Schema::table('Employer_list', function (Blueprint $table) {
            $table->boolean('今年是否同意參與QS')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Scholar_list', function (Blueprint $table) {
            $table->dropColumn('今年是否同意參與QS');
        });
        Schema::table('Employer_list', function (Blueprint $table) {
            $table->dropColumn('今年是否同意參與QS');
        });     
    }
};
