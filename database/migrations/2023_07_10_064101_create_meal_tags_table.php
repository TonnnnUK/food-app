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
        Schema::create('meal_tags', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('tag_name');
            $table->foreignId('user_id');
        
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
            });
                
            Schema::create('user_meal_tags', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->foreignId('meal_tag_id');
                $table->foreignId('meal_id');
                
                $table->foreign('meal_id')
                        ->references('id')
                        ->on('meals')
                        ->onDelete('cascade');
                
                $table->foreign('meal_tag_id')
                        ->references('id')
                        ->on('meal_tags')
                        ->onDelete('cascade');
            });
            
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_tags');
        Schema::dropIfExists('user_meal_tags');
    }
};
