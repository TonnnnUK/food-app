<?php

namespace App\Http\Controllers;

use App\Models\CustomWorkout;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomWorkoutController extends Controller
{
    public function index(User $user){
        
        return Inertia::render('Workouts', [
            'workouts' => $user->workouts,
            'can' => ['view' => Auth::user()->id == $user->id]
        ]);

    }

    public function view(User $user, CustomWorkout $workout){

        return Inertia::render('Workout_Info', [
            'workout' => $workout,
            'equipment' => $workout->getEquipmentNeeded(),
            'can' => ['view' => Auth::user()->id == $user->id]
        ]);

    }
}
