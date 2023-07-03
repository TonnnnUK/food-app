<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomWorkoutExerciseSet extends Model
{
    
    protected $with = ['exercise'];


    public function workout(){
        return $this->belongsTo(CustomWorkout::class, 'workout_id' );
    }
    
    
    public function exercise(){
        return $this->belongsTo(Exercise::class );
    }

}