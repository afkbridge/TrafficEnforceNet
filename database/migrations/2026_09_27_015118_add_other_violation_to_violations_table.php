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
        if (!Schema::hasColumn('violations', 'other_violation')) {
            Schema::table('violations', function (Blueprint $table) {
                $table->text('other_violation')
                    ->nullable()
                    ->after('violation_type_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('violations', 'other_violation')) {
            Schema::table('violations', function (Blueprint $table) {
                $table->dropColumn('other_violation');
            });
        }
    }
};