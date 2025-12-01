<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // 🧾 Basic Info
            $table->string('title');
            $table->string('address')->nullable();
            $table->string('area_name')->nullable();
            $table->string('property_type')->nullable();
            $table->string('contact')->nullable();
            $table->integer('total_units')->nullable();
            $table->integer('available_rooms')->nullable();
            $table->integer('tenants_needed')->nullable();
            $table->integer('current_tenants')->nullable();
            $table->string('contract_duration')->nullable();
            $table->string('gender')->nullable();             // ✅ Correct: property-level
            $table->string('furnished_level')->nullable();    // ✅ Correct: property-level
            $table->integer('total_bathrooms')->nullable();
            $table->json('images')->nullable();

            // 🧰 Amenities
            $table->json('amenities')->nullable();

            // 📍 Location
            $table->string('distance_ui_tm')->nullable();
            $table->string('nearby_shop')->nullable();
            $table->string('nearby_facility')->nullable();
            $table->text('location_advantages')->nullable();

            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
