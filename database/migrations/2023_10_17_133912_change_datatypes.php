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
            $table->boolean('去年是否同意參與QS')->nullable()->change();
            $table->boolean('今年是否同意參與QS')->nullable()->change();
        });
        Schema::table('Employer_list', function (Blueprint $table) {
            $table->boolean('去年是否同意參與QS')->nullable()->change();
            $table->boolean('今年是否同意參與QS')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Scholar_list', function (Blueprint $table) {
            $table->boolean('去年是否同意參與QS')->change();
            $table->boolean('今年是否同意參與QS')->change();
        });
        Schema::table('Employer_list', function (Blueprint $table) {
            $table->boolean('去年是否同意參與QS')->change();
            $table->boolean('今年是否同意參與QS')->change();
        });
    }
};
