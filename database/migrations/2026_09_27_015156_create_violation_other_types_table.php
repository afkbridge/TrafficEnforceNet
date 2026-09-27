<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('violation_other_types', function (Blueprint $table) {
            $table->id();

            $table->foreignId('violation_id')
                ->constrained('violations')
                ->cascadeOnDelete();

            $table->string('name', 255);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('violation_other_types');
    }
};