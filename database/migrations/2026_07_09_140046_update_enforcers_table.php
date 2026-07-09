<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enforcers', function (Blueprint $table) {
            $table->string('position')->after('email');
        });

        DB::statement("ALTER TABLE enforcers CHANGE status employment_status ENUM('Active','Inactive') NOT NULL DEFAULT 'Active'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE enforcers CHANGE employment_status status ENUM('Active','Inactive') NOT NULL DEFAULT 'Active'");

        Schema::table('enforcers', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }
};