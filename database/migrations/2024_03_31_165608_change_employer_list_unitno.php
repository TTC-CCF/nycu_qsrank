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
        Schema::table('Employer_list', function (Blueprint $table) {
            $table->integer("資料提供單位編號")->change();

        });
        Schema::table('Employer_list', function (Blueprint $table) {
            $table->renameColumn('資料提供單位編號', 'unitno'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Employer_list', function (Blueprint $table) {
            $table->renameColumn('unitno', '資料提供單位編號');
        });
        Schema::table('Employer_list', function (Blueprint $table) {
            $table->string("資料提供單位編號")->change();

        });
    }
};
