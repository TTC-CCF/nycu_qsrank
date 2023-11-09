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
            $table->string('資料提供者')->nullable();
            $table->renameColumn('資料提供單位Email', '資料提供者Email');
            $table->date('寄送Email日期')->nullable();
        });
        Schema::table('Employer_list', function (Blueprint $table) {
            $table->string('資料提供者')->nullable();
            $table->renameColumn('資料提供單位Email', '資料提供者Email');
            $table->date('寄送Email日期')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Scholar_list', function (Blueprint $table) {
            $table->dropColumn('資料提供者');
            $table->renameColumn('資料提供者Email', '資料提供單位Email');
            $table->dropColumn('寄送Email日期');
        });
        Schema::table('Employer_list', function (Blueprint $table) {
            $table->dropColumn('資料提供者');
            $table->renameColumn('資料提供者Email', '資料提供單位Email');
            $table->dropColumn('寄送Email日期');
        });
    }
};
