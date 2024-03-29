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
        //

        // Schema::table('users', function ($table) {

        //     $table->string('favoriteColor');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //

        Schema::table('users', function ($table) {

            $table->dropColumn('favoriteColor'); // Menghapus kolom favoriteColor
        });
    }
};


// php artisan make:migration add_favorite_color_column
// php artisan migrate

// php artisan migrate:rollback
// php artisan migrate:fresh