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
        Schema::create('player_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id')->nullable();
            $table->unsignedBigInteger('coach_id')->nullable();
            $table->date('date');
            $table->string('player_type');
            $table->integer('played_over')->nullable();  // Batsman
            $table->integer('today_give_wickets')->nullable();   // Batsman taken wicket
            $table->integer('through_over')->nullable(); // Bowler
            $table->integer('today_taken_wickets')->nullable();   // Bowler taken wicket
            $table->string('score_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_scores');
    }
};
