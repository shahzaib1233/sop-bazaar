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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->constrained('category')->cascadeOnDelete();
            $table->foreignId('subcategory_id')->constrained('sub_categories')->cascadeOnDelete();
            $table->integer('price');
            $table->foreignId('status_id')->constrained('status')->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('tags')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('ip_address')->nullable();
            $table->json('geoip_data')->nullable();   
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
