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
        Schema::create('return_equipment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id')->nullable();
            $table->unsignedBigInteger('coach_id')->nullable();
            $table->string('equipment_name')->nullable();
            $table->integer('quantity')->nullable();
            $table->dateTime('return_date_time')->nullable();
            $table->string('return_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_equipment');
    }
};
