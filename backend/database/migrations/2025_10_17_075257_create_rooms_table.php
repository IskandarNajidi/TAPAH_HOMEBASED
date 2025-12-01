<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');

            // 🏘️ Room Details
            $table->string('room_type')->nullable();
            $table->integer('capacity')->nullable();
            $table->integer('current_occupancy')->nullable();
            $table->string('bathroom_type')->nullable(); // Attached / Shared
            $table->string('bed_type')->nullable(); // Double Decker / Single Bed

            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
