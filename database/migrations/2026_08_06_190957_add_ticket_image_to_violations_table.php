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
        if (!Schema::hasColumn('violations', 'ticket_image')) {
            Schema::table('violations', function (Blueprint $table) {
                $table->string('ticket_image')
                      ->nullable()
                      ->after('remarks');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('violations', 'ticket_image')) {
            Schema::table('violations', function (Blueprint $table) {
                $table->dropColumn('ticket_image');
            });
        }
    }
};