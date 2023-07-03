<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserMeal extends Model
{
    use HasFactory;

    protected $table = "user_meals";

    protected $guarded = [];

    public function getDaysOldAttribute()
    {
        
        $today = Carbon::today();
        $date_in = Carbon::parse($this->date_in);
        if( $date_in == $today){
            return "today";
        } else {
            return $date_in->diffForHumans();
        }

    }
}
