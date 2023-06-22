<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Unit;
use Inertia\Inertia;
use App\Models\FoodItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MealsController extends Controller
{
    
    public function index()
    {

        $meals = Auth::user()->meals;

        return Inertia::render('Meals')->with([
            'meals' => $meals
        ]);
        
    }

    public function show(Meal $meal)
    {

        $units = Unit::all();

        if( request()->search){
            $search = request()->search;
            $foodItems = FoodItem::where('name', 'LIKE', "%$search%")->get();
        }

        return Inertia::render('Meal')->with([
            'meal' => $meal,
            'units' => $units,
            'foodItems' => isset($foodItems) ? $foodItems : null,
        ]);
        
    }

    public function save()
    {
        $addMeal = Meal::create([
            'name' => request('name'),
            'servings' => request('servings'),  
            'user_id' => Auth::user()->id,
        ]);

        return redirect("/meal/$addMeal->id");
    }

    public function addIngredient(Meal $meal){

        $meal->ingredients()->attach([request('id')], [
            'food_item_id' => request('id'),
            'unit_id' => request('unitID'),
            'unit' => request('unit'),
            'meal_id' => $meal->id,
            'qty' => request('qty'),
        ]);

        return redirect("/meal/$meal->id");
        
    }

    public function removeIngredient(Meal $meal, FoodItem $fooditem){

        $meal->ingredients()->detach([$fooditem->id]);

        return redirect("/meal/$meal->id");
        
        
        // $units = Unit::all();

        // if( request()->search){
        //     $search = request()->search;
        //     $foodItems = FoodItem::where('name', 'LIKE', "%$search%")->get();
        // }

        // return Inertia::render('Meal')->with([
        //     'meal' => $meal,
        //     'units' => $units,
        //     'foodItems' => isset($foodItems) ? $foodItems : null,
        // ]);
        
    }

}
