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
        Schema::table('entries', function (Blueprint $table) {
            $table->unsignedBigInteger('recipe_id')->nullable()->change();
            $table->unsignedBigInteger('meal_id')->nullable()->change();
            $table->unsignedBigInteger('workout_id')->nullable()->change();
            $table->unsignedBigInteger('custom_workout_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entries', function (Blueprint $table) {
             // If you want to revert the changes, you can make the foreign key fields non-nullable again
            $table->unsignedBigInteger('recipe_id')->nullable(false)->change();
            $table->unsignedBigInteger('meal_id')->nullable(false)->change();
            $table->unsignedBigInteger('workout_id')->nullable(false)->change();
            $table->unsignedBigInteger('custom_workout_id')->nullable(false)->change();
         
        });
    }
};
