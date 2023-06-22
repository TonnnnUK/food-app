<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\MealsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\InventoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Home', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'name' => Auth::user()->name
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/planner', function () {
        return Inertia::render('Planner');
    })->name('planner');

    Route::get('/inventory',  [InventoryController::class, 'index'])
        ->name('inventory');

    Route::post('/inventory',  [InventoryController::class, 'addLocation'])
        ->name('add-inventory-locations');

    Route::post('/inventory/add-items',  [InventoryController::class, 'addFoodItems'])
        ->name('add-food-items');

    Route::post('/inventory/{location}/{item}/remove',  [InventoryController::class, 'removeFoodItem'])
        ->name('remove-inventory-item');
        

    // TODO: 
    Route::get('/inventory/{id}/meals',  [MealsController::class, 'mealsByItem'])
        ->name('meals-by-item');

    Route::get('/meals', [MealsController::class, 'index'])
        ->name('meals');
    
    Route::post('/meals', [MealsController::class, 'save'])
        ->name('add-meal');
            
        
    Route::get('/meal/{meal}', [MealsController::class, 'show'])
        ->name('meal-info');
        
    Route::post('/meal/{meal}', [MealsController::class, 'addIngredient'])
        ->name('add-ingredient');
    
    Route::post('/meal/{meal}/remove/{fooditem}', [MealsController::class, 'removeIngredient'])
        ->name('remove-ingredient');


    Route::get('food', [FoodController::class, 'index'])
        ->name('food-index');
        
    Route::post('food', [FoodController::class, 'save'])
        ->name('add-food-item');
        

});

Route::get('/exercises', [ExerciseController::class, 'index'])
        ->name('exercises');

Route::get('/exercise/{exercise:slug}', [ExerciseController::class, 'show'])
        ->name('exercise-info');

Route::get('/workouts', [WorkoutController::class, 'index'])->name('workouts');
Route::get('/workouts/{workout:slug}', [WorkoutController::class, 'show'])->name('workout-info');

require __DIR__.'/auth.php';
