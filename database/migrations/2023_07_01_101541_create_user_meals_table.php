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
        Schema::create('user_meals', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('meal_id');
            $table->foreignId('user_id');
            $table->foreignId('location_id');
            $table->string('qty')->nullable();
            $table->foreignId('unit_id')->nullable();
            $table->date('date_in');
            $table->date('date_out')->nullable();
            $table->integer('days_expire')->nullable();
            $table->boolean('freezeable')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_meals');
    }
};
