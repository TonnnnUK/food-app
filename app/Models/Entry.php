<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{


    protected $table = 'entries';

    protected $guarded = []; 

    protected $with = [
        'meal',
        'recipe',
        'workout',
        'custom_workout',
    ];

    protected $casts = [
        'entry_data' => 'array',
        'date_time' => 'datetime:Y-m-d'
    ];

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
    
    public function workout()
    {
        return $this->belongsTo(Workout::class);
    }
    
    public function custom_workout()
    {
        return $this->belongsTo(CustomWorkout::class);
    }

    public function getDataNotesAttribute(){
        return $this->entry_data['notes'];
    }

    public function getDataNameAttribute(){
        return $this->entry_data['name'];
    }

}
