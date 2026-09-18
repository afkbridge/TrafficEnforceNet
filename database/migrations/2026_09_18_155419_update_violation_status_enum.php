<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('violations', function (Blueprint $table) {
            $table->enum('status', [
                'Pending',
                'Settled'
            ])->default('Pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('violations', function (Blueprint $table) {
            $table->enum('status', [
                'Pending',
                'Synced',
                'Completed',
                'Cancelled'
            ])->default('Pending')->change();
        });
    }
};