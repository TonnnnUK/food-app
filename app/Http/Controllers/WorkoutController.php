<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Workout;
use Illuminate\Http\Request;

class WorkoutController extends Controller
{
    public function index()
    {
        $workouts = Workout::all();
        return Inertia::render('Workouts')->with([
            'workouts' => $workouts
        ]);
        
    }

    public function show(Workout $workout) {
        
        return Inertia::render('Workout_Info')->with([
            'workout' => $workout
        ]);
    }
}
