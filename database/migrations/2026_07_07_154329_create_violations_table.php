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
    Schema::create('violations', function (Blueprint $table) {
        $table->id();

        // Ticket Number
        $table->string('ticket_number')->unique();

        // Relationships
        $table->foreignId('driver_id')
              ->constrained()
              ->cascadeOnUpdate()
              ->restrictOnDelete();

        $table->foreignId('vehicle_id')
              ->constrained()
              ->cascadeOnUpdate()
              ->restrictOnDelete();

        $table->foreignId('violation_type_id')
              ->constrained()
              ->cascadeOnUpdate()
              ->restrictOnDelete();

        // Enforcer (User)
        $table->foreignId('user_id')
              ->constrained()
              ->cascadeOnUpdate()
              ->restrictOnDelete();

        // Violation Details
        $table->date('violation_date');
        $table->time('violation_time');

        $table->string('location');

        $table->decimal('latitude', 10, 7)->nullable();
        $table->decimal('longitude', 10, 7)->nullable();

        $table->text('remarks')->nullable();

        // Workflow Status
        $table->enum('status', [
            'Pending',
            'Synced',
            'Completed',
            'Cancelled'
        ])->default('Pending');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violations');
    }
};
