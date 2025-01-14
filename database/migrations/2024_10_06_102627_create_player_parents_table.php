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
        Schema::create('player_parents', function (Blueprint $table) {
            $table->id();
            $table->string('cnic');
            $table->string('name');
            $table->string('email')->unique();
            $table->text('address');
            $table->unsignedBigInteger('player_id');
            $table->string('phone_number');
            $table->string('location');
            $table->string('status')->default('active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_parents');
    }
};
