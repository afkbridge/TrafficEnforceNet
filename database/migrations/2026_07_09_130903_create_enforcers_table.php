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
    Schema::create('enforcers', function (Blueprint $table) {
        $table->id();

        $table->string('badge_number')->unique();

        $table->string('first_name');

        $table->string('middle_name')->nullable();

        $table->string('last_name');

        $table->string('contact_number')->nullable();

        $table->string('email')->nullable();

        $table->enum('status', [
            'Active',
            'Inactive'
        ])->default('Active');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enforcers');
    }
};
