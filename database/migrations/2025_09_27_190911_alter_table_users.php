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
          Schema::table('users', function (Blueprint $table) {
            // add after 'password' (order is optional)
            $table->boolean('is_active')->default(true)->after('password');
        });

        // Optional: backfill existing rows explicitly (some DBs won't set the default on existing rows)
        DB::table('users')->whereNull('is_active')->update(['is_active' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
           Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
