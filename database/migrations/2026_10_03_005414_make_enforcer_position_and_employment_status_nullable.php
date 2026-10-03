<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enforcers', function (Blueprint $table) {
            $table->string('position')
                ->nullable()
                ->change();

            $table->enum('employment_status', [
                'Active',
                'Inactive',
            ])
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('enforcers', function (Blueprint $table) {
            $table->string('position')
                ->nullable(false)
                ->change();

            $table->enum('employment_status', [
                'Active',
                'Inactive',
            ])
                ->nullable(false)
                ->change();
        });
    }
};