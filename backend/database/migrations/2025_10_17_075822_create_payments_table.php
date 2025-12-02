<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // 🧩 Relationships
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('property_id')->nullable();
            $table->unsignedBigInteger('room_id')->nullable();

            // 💳 Payment Details
            $table->string('payment_type', 255)->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->decimal('deposit', 10, 2)->nullable();
            $table->string('utility')->nullable();
            $table->decimal('additional_bill', 10, 2)->nullable();
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
            $table->string('reference')->nullable();
            $table->text('note')->nullable();

            $table->timestamps();

            // ✅ Explicit Foreign Key Constraints (with cascade delete)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Drop constraints first (for safety)
            $table->dropForeign(['user_id']);
            $table->dropForeign(['property_id']);
            $table->dropForeign(['room_id']);
        });

        Schema::dropIfExists('payments');
    }
};
