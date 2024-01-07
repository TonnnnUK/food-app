<?php

namespace App\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class CustomWorkout extends Model
{
 
    protected $key = 'slug';
    
    protected $fillable = [
        'name', 
        'slug', 
        'notes',
        'routine',
        'user_id',
    ];

    protected $casts = [
        'routine' => 'array',
    ];

    protected $with = ['exerciseSets'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function exerciseSets(){
        return $this->hasMany(CustomWorkoutExerciseSet::class);
    }

    public static function createSlug($user, $title)
    {
        $slug = Str::slug($user .' '. $title);

        $count = CustomWorkout::where('slug', $slug)->count();
        if ( $count > 0 ){
            $slug = $slug .'-'. intval( $count+1 );
        }

        return $slug;

    }

    public function getEquipmentNeeded(){

        $exerciseIDs = $this->exerciseSets->pluck('exercise_id')->unique()->toArray();;
        $exercises = Exercise::with(['equipment' => function ($query) {
                                $query->select('equipment.name'); // Select only the columns you need from the equipment table
                            }])->whereIn('id', $exerciseIDs)
                            ->get();
        $equipment = [];
        $names = [];

        foreach($exercises as $exercise){
           
            foreach($exercise->equipment as $eq){
                
                if( !in_array( $eq->name, $names ) ){                    
                    $names[] = $eq->name;
                    $equipment[] = $eq;
                }
            }
            
        }

        return $equipment;

    }
}
