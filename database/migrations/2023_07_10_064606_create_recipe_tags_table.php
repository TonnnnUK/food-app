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
        Schema::create('recipe_tags', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('tag_name');
        });
        
        Schema::create('recipe_tags_tag', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('recipe_tag_id');
            $table->foreignId('recipe_id');

            $table->foreign('recipe_id')
                        ->references('id')
                        ->on('recipes')
                        ->onDelete('cascade');
                
            $table->foreign('recipe_tag_id')
                    ->references('id')
                    ->on('recipe_tags')
                    ->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_tags');
        Schema::dropIfExists('user_recipe_tags');
    }
};
