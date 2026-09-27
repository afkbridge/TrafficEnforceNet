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
        Schema::table('trusted_devices', function (Blueprint $table) {
            // Remove the global unique constraint on device_token.
            $table->dropUnique('trusted_devices_device_token_unique');

            // Allow the same device token for different users,
            // while preventing duplicate user + device token combinations.
            $table->unique(
                ['user_id', 'device_token'],
                'trusted_devices_user_device_token_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trusted_devices', function (Blueprint $table) {
            // Remove the composite unique constraint.
            $table->dropUnique('trusted_devices_user_device_token_unique');

            // Restore the original global unique constraint.
            $table->unique(
                'device_token',
                'trusted_devices_device_token_unique'
            );
        });
    }
};