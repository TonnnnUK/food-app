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
        Schema::table('custom_workout_exercise_sets', function( Blueprint $table ){
            $table->dropForeign(['workout_exercise_id']);
            $table->dropColumn('workout_exercise_id');
            $table->foreignId('custom_workout_id')->after('id');
            $table->foreignId('exercise_id')->after('custom_workout_id');
            
            $table->foreign('custom_workout_id')
                ->references('id')
                ->on('custom_workouts')
                ->onDelete('cascade');

            $table->foreign('exercise_id')
                ->references('id')
                ->on('exercises')
                ->onDelete('cascade');
        });


        Schema::drop('custom_workout_exercises');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_workout_exercise_sets', function( Blueprint $table ){
            $table->foreignId('workout_exercise_id');
            $table->dropColumn('custom_workout_id');
            $table->dropColumn('exercise_id');

            $table->foreign('workout_exercise_id')
                ->references('id')
                ->on('custom_workout_exercises')
                ->onDelete('cascade');
        });
    }
};
