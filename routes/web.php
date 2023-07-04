<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\MealsController;
use App\Http\Controllers\PlannerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\DashboardController;
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
    return redirect('/dashboard');
    // return Inertia::render('Home', [
    //     'canLogin' => Route::has('login'),
    //     'canRegister' => Route::has('register'),
    //     'laravelVersion' => Application::VERSION,
    //     'phpVersion' => PHP_VERSION,
    // ]);
})->name('home');


Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])
        ->name('dashboard');

    Route::post('/dashboard/{entry}/complete', [DashboardController::class, 'complete'])->name('complete-entry');
    Route::post('/dashboard/leftovers', [DashboardController::class, 'leftovers'])->name('add-leftovers');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/planner', [PlannerController::class, 'index'])->name('planner');
    Route::post('/entry/add', [PlannerController::class, 'addEntry'])->name('add-entry');
    Route::patch('/entry/update', [PlannerController::class, 'updateEntry'])->name('update-entry');
    Route::delete('/entry/{entry}', [PlannerController::class, 'destroy'])->name('delete-entry');

    Route::get('/inventory',  [InventoryController::class, 'index'])->name('inventory');
    Route::post('/inventory',  [InventoryController::class, 'addLocation'])->name('add-inventory-locations');
    Route::post('/inventory/add-items',  [InventoryController::class, 'addFoodItems'])->name('add-food-items');
    Route::post('/inventory/remove',  [InventoryController::class, 'removeFoodItem'])->name('remove-inventory-item');
    Route::get('/inventory/{id}/meals',  [MealsController::class, 'mealsByItem'])->name('meals-by-item'); // TODO: 

    Route::get('/meals', [MealsController::class, 'index'])->name('meals');
    Route::post('/meals', [MealsController::class, 'save'])->name('add-meal');

    Route::get('/meal/{meal}', [MealsController::class, 'show'])->name('meal-info');
    Route::post('/meal/{meal}', [MealsController::class, 'addIngredient'])->name('add-ingredient');
    Route::post('/meal/{meal}/save-title', [MealsController::class, 'saveTitle'])->name('save-meal-title');
    Route::post('/meal/{meal}/remove/{fooditem}', [MealsController::class, 'removeIngredient']) ->name('remove-ingredient');
    Route::post('/meal/{meal}/duplicate', [MealsController::class, 'duplicate'])->name('duplicate-meal');
    Route::post('/meal/{meal}/add-to-shopping-list', [MealsController::class, 'addToList'])->name('add-meal-items-to-shopping-list');


    Route::get('food', [FoodController::class, 'index'])->name('food-index');
    Route::post('food', [FoodController::class, 'save'])->name('add-food-item');

    Route::post('shopping-list/add/{fooditem}', [PlannerController::class, 'addToList'])->name('add-to-shopping-list');
    Route::post('shopping-list/remove/{fooditem}', [PlannerController::class, 'removeFromList'])->name('remove-from-shopping-list');
});

Route::get('/exercises', [ExerciseController::class, 'index'])->name('exercises');
Route::get('/exercise/{exercise:slug}', [ExerciseController::class, 'show'])->name('exercise-info');

Route::get('/workouts', [WorkoutController::class, 'index'])->name('workouts');
Route::get('/workouts/{workout:slug}', [WorkoutController::class, 'show'])->name('workout-info');

require __DIR__.'/auth.php';
