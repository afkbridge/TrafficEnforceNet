<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('violation_violation_type', function (Blueprint $table) {
            $table->id();

            $table->foreignId('violation_id')
                ->constrained('violations')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('violation_type_id')
                ->constrained('violation_types')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->timestamps();

            $table->unique([
                'violation_id',
                'violation_type_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('violation_violation_type');
    }
};