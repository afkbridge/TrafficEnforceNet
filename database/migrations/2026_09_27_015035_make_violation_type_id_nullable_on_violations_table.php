<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('violations', function (Blueprint $table) {
            $table->dropForeign(['violation_type_id']);
        });

        Schema::table('violations', function (Blueprint $table) {
            $table->foreignId('violation_type_id')
                ->nullable()
                ->change();
        });

        Schema::table('violations', function (Blueprint $table) {
            $table->foreign('violation_type_id')
                ->references('id')
                ->on('violation_types')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('violations', function (Blueprint $table) {
            $table->dropForeign(['violation_type_id']);
        });

        Schema::table('violations', function (Blueprint $table) {
            $table->foreignId('violation_type_id')
                ->nullable(false)
                ->change();
        });

        Schema::table('violations', function (Blueprint $table) {
            $table->foreign('violation_type_id')
                ->references('id')
                ->on('violation_types')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }
};