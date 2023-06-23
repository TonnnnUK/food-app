<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Inertia\Inertia;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlannerController extends Controller
{
    
    public $inventory_count = 0;

    public function index()
    {

        $meals = Auth::user()->meals;
        $meals->map( function( Meal $meal, int $key ){
            $ingredientIds = $meal->ingredients->pluck('id');
            $meal->ingredient_count = count($ingredientIds);
            $inventory = Auth::user()->food_items->pluck('id')->toArray();

            $matchingIngredients = $ingredientIds->intersect($inventory);
            $meal->inventory_match = $matchingIngredients->count();

            if( count($inventory) != 0 && $meal->inventory_match != 0){
                $meal->match_percent = number_format($meal->inventory_match / count($ingredientIds) * 100);
            }
        });

        
        $sortedMeals = $meals->sortByDesc('match_percent')->values();

        return Inertia::render('Planner')->with([
            'meals' => $sortedMeals
        ]);
    }
}
