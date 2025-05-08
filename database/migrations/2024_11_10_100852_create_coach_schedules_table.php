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
        Schema::create('coach_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coach_id');
            $table->unsignedBigInteger('player_id');
            $table->time('start_time'); // Change this to time
            $table->time('end_time');   // Change this to time if only storing time
            $table->date('to_date');
            $table->date('from_date');
            $table->unsignedBigInteger('booking_slot')->nullable();
            $table->string('event_name');
            $table->string('status')->default('processing');
            $table->integer('booking_slot');
            $table->string('playwith');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }
// ALTER TABLE `coach_schedules` CHANGE `booking_slot` `booking_slot` BIGINT(20) UNSIGNED NOT NULL;
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coach_schedules');
    }
};
