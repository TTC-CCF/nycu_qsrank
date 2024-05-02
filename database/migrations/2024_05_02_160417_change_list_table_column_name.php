<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            Schema::table("Employer_list", function (Blueprint $table) {
                $table->renameColumn("資料提供單位", "unit_name");
                $table->renameColumn("資料提供者Email", "provider_email");
                $table->renameColumn("資料提供者", "provider_name");
                $table->renameColumn("Title", "title");
                $table->renameColumn("First_name", "first_name");
                $table->renameColumn("Last_name", "last_name");
                $table->renameColumn("Chinese_name", "chinese_name");
                $table->renameColumn("Position", "position");
                $table->renameColumn("Industry", "industry");
                $table->renameColumn("CompanyName", "company_name");
                $table->renameColumn("BroadSubjectArea", "broad_subject_area");
                $table->renameColumn("MainSubject", "main_subject");
                $table->renameColumn("Location", "location");
                $table->renameColumn("寄送Email日期", "sent_email_date");
                $table->renameColumn("Email", "email");
                $table->dropColumn(["去年是否同意參與QS", "今年是否同意參與QS"]);

            });

            Schema::table("Scholar_list", function (Blueprint $table) {
                $table->renameColumn("資料提供單位", "unit_name");
                $table->renameColumn("資料提供者Email", "provider_email");
                $table->renameColumn("資料提供者", "provider_name");
                $table->renameColumn("Title", "title");
                $table->renameColumn("First_name", "first_name");
                $table->renameColumn("Last_name", "last_name");
                $table->renameColumn("Chinese_name", "chinese_name");
                $table->renameColumn("Job_title", "job_title");
                $table->renameColumn("Department", "department");
                $table->renameColumn("Institution", "institution");
                $table->renameColumn("BroadSubjectArea", "broad_subject_area");
                $table->renameColumn("MainSubject", "main_subject");
                $table->renameColumn("Country", "location");
                $table->renameColumn("寄送Email日期", "sent_email_date");
                $table->renameColumn("Email", "email");
                $table->renameColumn("Phone", "phone");
                $table->dropColumn(["去年是否同意參與QS", "今年是否同意參與QS"]);

            });
        } catch (Exception $err) {
            DB::rollback();
            throw $err;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        try {
            Schema::table("Employer_list", function (Blueprint $table) {
                $table->renameColumn("unit_name", "資料提供單位");
                $table->renameColumn("provider_email", "資料提供者Email");
                $table->renameColumn("provider_name", "資料提供者");
                $table->renameColumn("title", "Title");
                $table->renameColumn("first_name", "First_name");
                $table->renameColumn("last_name", "Last_name");
                $table->renameColumn("chinese_name", "Chinese_name");
                $table->renameColumn("position", "Position");
                $table->renameColumn("industry", "Industry");
                $table->renameColumn("company_name", "CompanyName");
                $table->renameColumn("broad_subject_area", "BroadSubjectArea");
                $table->renameColumn("main_subject", "MainSubject");
                $table->renameColumn("location", "Location");
                $table->renameColumn("sent_email_date", "寄送Email日期");
                $table->renameColumn("email", "Email");
                $table->boolean("去年是否同意參與QS");
                $table->boolean("今年是否同意參與QS");

            });

            Schema::table("Scholar_list", function (Blueprint $table) {
                $table->renameColumn("unit_name", "資料提供單位");
                $table->renameColumn("provider_email", "資料提供者Email");
                $table->renameColumn("provider_name", "資料提供者");
                $table->renameColumn("title", "Title");
                $table->renameColumn("first_name", "First_name");
                $table->renameColumn("last_name", "Last_name");
                $table->renameColumn("chinese_name", "Chinese_name");
                $table->renameColumn("job_title", "Job_title");
                $table->renameColumn("department", "Department");
                $table->renameColumn("institution", "Institution");
                $table->renameColumn("broad_subject_area", "BroadSubjectArea");
                $table->renameColumn("main_subject", "MainSubject");
                $table->renameColumn("location", "Location");
                $table->renameColumn("sent_email_date", "寄送Email日期");
                $table->renameColumn("email", "Email");
                $table->renameColumn("phone", "Phone");
                $table->boolean("去年是否同意參與QS");
                $table->boolean("今年是否同意參與QS");

            });
        } catch (Exception $err) {
            DB::rollback();
            throw $err;
        }
    }
};
