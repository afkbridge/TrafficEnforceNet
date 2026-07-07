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
    Schema::create('drivers', function (Blueprint $table) {
        $table->id();

        $table->string('license_number')->unique();

        $table->string('first_name');
        $table->string('middle_name')->nullable();
        $table->string('last_name');

        $table->string('address');

        $table->date('birth_date')->nullable();

        $table->string('contact_number')->nullable();

        $table->string('license_type')->nullable();

        $table->date('license_expiration')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
