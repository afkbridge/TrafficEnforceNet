<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('violations', function (Blueprint $table) {

            $table->string('ticket_image')
                  ->nullable()
                  ->after('remarks');

        });
    }


    public function down(): void
    {
        Schema::table('violations', function (Blueprint $table) {

            $table->dropColumn('ticket_image');

        });
    }
};