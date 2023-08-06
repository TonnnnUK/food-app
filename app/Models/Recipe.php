<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{

    protected $guarded = [];
    protected $with = ['ingredients'];

    public function ingredients()
    {
        return $this->belongsToMany(FoodItem::class, 'recipe_food_items');
    }

    public function tags()
    {
        return $this->belongsToMany(RecipeTag::class, 'recipe_tags_tag');
    }
}
