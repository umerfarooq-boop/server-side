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
        Schema::create('checkout_forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id');
            $table->unsignedBigInteger('coach_id');
            $table->unsignedBigInteger('booking_id');
            $table->string('player_name');
            $table->string('player_email');
            $table->unsignedBigInteger('player_phone_number');
            $table->string('player_address');
            $table->string('coach_name');
            $table->time('start_time');
            $table->time('end_time');
            $table->date('to_date');
            $table->date('from_date');
            $table->unsignedBigInteger('per_hour_charges')->nullable();
            $table->unsignedBigInteger('total_charges')->nullable();
            $table->string('payment_type')->default('stripe');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkout_forms');
    }
};
