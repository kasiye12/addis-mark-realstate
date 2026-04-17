<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Property Details
            $table->decimal('price', 15, 2);
            $table->enum('price_type', ['sale', 'rent'])->default('sale');
            $table->enum('property_type', ['apartment', 'villa', 'commercial', 'land', 'office'])->default('apartment');
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('area_sqm')->nullable();
            $table->integer('year_built')->nullable();
            
            // Features
            $table->boolean('parking')->default(false);
            $table->boolean('furnished')->default(false);
            $table->boolean('security')->default(false);
            $table->boolean('elevator')->default(false);
            $table->boolean('garden')->default(false);
            $table->boolean('pool')->default(false);
            $table->boolean('air_conditioning')->default(false);
            
            // Status
            $table->enum('status', ['available', 'sold', 'rented', 'pending'])->default('available');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('views')->default(0);
            
            // Additional Info
            $table->string('address')->nullable();
            $table->string('video_url')->nullable();
            $table->string('virtual_tour_url')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['price_type', 'status', 'is_featured']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('properties');
    }
};