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
        Schema::table('vehicles', function (Blueprint $table) {

            // Remove fields not found on the POSO ticket
            $table->dropColumn([
                'brand',
                'model',
                'color',
                'engine_number',
                'chassis_number',
            ]);

            // Add fields found on the POSO ticket
            $table->string('region_number')->nullable()->after('vehicle_type');

            $table->string('owner_name')->nullable()->after('region_number');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {

            $table->string('brand');

            $table->string('model');

            $table->string('color')->nullable();

            $table->string('engine_number')->nullable();

            $table->string('chassis_number')->nullable();

            $table->dropColumn([
                'region_number',
                'owner_name',
            ]);

        });
    }
};