<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['name', 'user_id', 'required'];


    public static function createLocationsForNewUser($user)
    {
        $defaultlocations = [
            'Fridge',
            'Freezer',
            'Cupboard',
        ] ;

        foreach ($defaultlocations as $location){
            Location::create([
                'name' => $location,
                'user_id' => $user->id,
                'required' => 1
            ]);
        }
    }

    public function items()
    {
        return $this->belongsToMany(FoodItem::class, 'user_food_items')
                    ->withPivot(['id', 'user_id', 'qty', 'unit_id', 'date_in', 'date_out', 'days_expire', 'freezeable']);
    }

    public function meals()
    {
        return $this->belongsToMany(Meal::class, 'user_meals')
                    ->withPivot(['id', 'user_id', 'qty', 'unit_id', 'date_in', 'date_out', 'days_expire', 'freezeable']);
    }

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'user_recipes')
                    ->withPivot(['id', 'user_id', 'qty', 'unit_id', 'date_in', 'date_out', 'days_expire', 'freezeable']);
    }
}
