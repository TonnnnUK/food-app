<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ExerciseController extends Controller
{
    public function index()
    {

        $exercises = Exercise::all();

        return Inertia::render('Exercises', [    
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ])->with([
            'exercises' => $exercises
        ]);
        
    }

    public function show(Exercise $exercise) {
        
        return Inertia::render('Exercise_Info')->with([
            'exercise' => $exercise
        ]);
    }
}
