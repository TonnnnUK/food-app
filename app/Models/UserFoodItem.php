<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserFoodItem extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


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
