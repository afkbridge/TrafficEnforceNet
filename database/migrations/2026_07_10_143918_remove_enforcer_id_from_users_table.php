<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['enforcer_id']);
            $table->dropColumn('enforcer_id');

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->foreignId('enforcer_id')
                ->nullable()
                ->constrained('enforcers')
                ->nullOnDelete();

        });
    }
};