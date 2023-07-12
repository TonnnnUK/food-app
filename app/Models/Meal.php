<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meal extends Model
{
    
    use HasFactory;

    protected $guarded = [];
    protected $with = ['ingredients'];

    public function path()
    {
        return "{$this->user->id}/meal/{$this->id}";
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function ingredients()
    {
        return $this->belongsToMany(FoodItem::class, 'meal_food_items', 'meal_id', 'food_item_id')
                    ->withPivot(['qty', 'unit', 'unit_id'])
                    ->withTimestamps();
    }

    public function tags()
    {
        return $this->belongsToMany(MealTag::class, 'user_meal_tags');
    }
}
