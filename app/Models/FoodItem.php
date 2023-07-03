<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodItem extends Model
{

    protected $guarded = [];

    public function unit()
    {
        return $this->hasManyThrough(Unit::class, Meal::class);
    }

    public function location(){
        return $this->belongsToMany(User::class, 'user_food_items', 'location_id')
                    ->withPivot(['id', 'user_id', 'qty', 'unit_id', 'date_in', 'date_out', 'days_expire', 'freezeable']);
    }
}
