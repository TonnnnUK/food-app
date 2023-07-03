<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{

    protected $with = ['ingredients'];

    public function ingredients()
    {
        return $this->belongsToMany(FoodItem::class, 'recipe_food_items');
    }
}
