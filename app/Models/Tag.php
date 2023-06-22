<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function exercises()
    {
        return $this->belongsToMany('App\Exercise')->withTimestamps();
    }

    public function workouts()
    {
        return $this->belongsToMany('App\Workout')->withTimestamps();
    }

}
