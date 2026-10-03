
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
        /*
        |--------------------------------------------------------------------------
        | Make badge_number optional
        |--------------------------------------------------------------------------
        |
        | The unique index already exists on badge_number.
        | Therefore, do NOT call ->unique() here again.
        |
        */

        Schema::table('enforcers', function (Blueprint $table) {
            $table->string('badge_number')
                ->nullable()
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Restore badge_number as required
        |--------------------------------------------------------------------------
        |
        | The existing unique index is preserved.
        | Therefore, do NOT add ->unique() here either.
        |
        */

        Schema::table('enforcers', function (Blueprint $table) {
            $table->string('badge_number')
                ->nullable(false)
                ->change();
        });
    }
};

