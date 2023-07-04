<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Unit;
use Inertia\Inertia;
use App\Models\FoodItem;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MealsController extends Controller
{
    
    public function index()
    {
        $meals = Auth::user()->meals;
        $sorted = $meals->sortBy('name')->values();

        return Inertia::render('Meals')->with([
            'meals' => $sorted
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

    public function saveTitle(Meal $meal){
        
        $meal->name = request('newTitle');
        $meal->save();

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

    public function duplicate(Meal $meal)
    {
        $ingredients = $meal->ingredients;
        $mealArray = $meal->toArray();
        Arr::forget($mealArray, ['id', 'ingredients', 'created_at', 'updated_at']);
        
        $new = Meal::create($mealArray);
        
        $ingredients->map(function( $ing ) use ( $new ){
            
            $ing->pivot->meal_id = $new->id;
            $insert = $ing->pivot->toArray();
            Arr::forget($insert, ['created_at', 'updated_at']);
            $id = DB::table('meal_food_items')->insertGetId($insert);
        });

        return redirect("/meal/$new->id");

    }

    public function addToList(Meal $meal){
        $mealItems = $meal->ingredients->pluck('id');
        $inventoryItems = Auth::user()->food_items->pluck('id')->toArray();
        $shoppingListItems = Auth::user()->shopping_list->pluck('id')->toArray();
        $allItems = array_merge($inventoryItems, $shoppingListItems);    
        $items = $mealItems->filter( function ($item) use ($allItems) {
            if( in_array($item, $allItems) ){
                return $item;
            }
        });

        Auth::user()->shopping_list()->attach($items);

        return redirect("/planner");
    }

}
