<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function food_items(){
        return $this->belongsToMany(FoodItem::class, 'user_food_items')->withTimestamps();
    }

    public function workouts(){
        return $this->hasMany(CustomWorkout::class);
    }

    public function entries(){
        return $this->hasMany(Entry::class);
    }

    public function leftovers(){
        return $this->hasMany(Meal::class, 'user_meals');
    }

    public function shopping_list(){
        return $this->belongsToMany(FoodItem::class, 'shopping_list', 'user_id', 'food_item_id')->withTimestamps();
    }

}
