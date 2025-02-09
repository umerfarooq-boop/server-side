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
        Schema::create('edit_appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coach_schedule_id')->nullable();
            $table->unsignedBigInteger('coach_id')->nullable();
            $table->unsignedBigInteger('player_id')->nullable();
            $table->time('start_time'); // Change this to time
            $table->time('end_time');   // Change this to time if only storing time
            $table->date('to_date');
            $table->date('from_date');
            $table->unsignedBigInteger('booking_slot')->nullable();
            $table->string('event_name');
            $table->string('status')->default('processing');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('edit_appointments');
    }
};
