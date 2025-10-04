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
         Schema::table('permissions', function (Blueprint $table) {
            // add after 'password' (order is optional)
            $table->boolean('is_active')->default(true);
            $table->string('slug')->default(true)->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('permission', function (Blueprint $table) {
        //     $table->dropColumn('is_active');
        //     $table->dropColumn('slug');

        // });
    }
};
