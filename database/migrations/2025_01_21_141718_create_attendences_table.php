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
        Schema::create('attendences', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->date('to_date')->nullable();
            $table->date('from_date')->nullable();
            $table->string('attendance_status')->nullable();
            $table->unsignedBigInteger('coach_id')->nullable();
            $table->unsignedBigInteger('appointment_id')->nullable();
            $table->unsignedBigInteger('player_id')->nullable();
            $table->timestamps();
        });
    }
    //ALTER TABLE `attendences` ADD `to_date` DATE NULL DEFAULT NULL AFTER `end_time`;
    //ALTER TABLE `attendences` ADD `from_date` DATE NULL DEFAULT NULL AFTER `to_date`;
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendences');
    }
};
