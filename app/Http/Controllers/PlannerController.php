<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Meal;
use Inertia\Inertia;
use App\Models\Entry;
use App\Models\Recipe;
use App\Models\Workout;
use App\Models\FoodItem;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\CustomWorkout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PlannerController extends Controller
{
    
    public $inventory_count = 0;

    public function index()
    {

        $user = Auth::user();

        $recipes = Recipe::all();
        $meals = $user->meals;
        $inventory = $user->food_items;
        $inventoryIds = $inventory->pluck('id')->toArray();
        
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        $entries = Entry::where('user_id', $user->id)->get();

        $meals->map( function( Meal $meal, int $key ) use ( $inventory, $inventoryIds) {
            $ingredientIds = $meal->ingredients->pluck('id');
            $meal->ingredient_count = count($ingredientIds);

            
            $matchingIngredients = $ingredientIds->intersect($inventoryIds);
            $meal->inventory_match = $matchingIngredients->count();
            
            if( count($inventory) != 0 && $meal->inventory_match != 0){
                $meal->match_percent = number_format($meal->inventory_match / count($ingredientIds) * 100);
            }
            

            $filteredIngredients = $meal->ingredients->reject(function ($ingredient) use ($inventoryIds) {
                return in_array($ingredient->id, $inventoryIds);
            });

            $meal->missingItems = $filteredIngredients;
        
        });

        
        $sortedMeals = $meals->sortByDesc('match_percent')->values();

        $custom_workouts = $user->workouts;
        
        $shopping_list = $user->shopping_list->map( function($item){
            $item->shopping_list_id = $item->pivot['id'];
            return $item;
        });
    
        $shopping_list = $shopping_list->sortBy('name')->values();
        $shopping_list_ids = $shopping_list->pluck('id')->toArray();
        $workouts = Workout::all();

        if( isset(request()->search) ){
            $search = request()->search;
            $foodItems = FoodItem::where('name', 'LIKE', "%$search%")->get();
        }

        return Inertia::render('Planner')->with([
            'recipes' => $recipes,
            'meals' => $sortedMeals,
            'workouts' => $workouts,
            'custom_workouts' => $custom_workouts,
            'entries' => $entries,
            'shopping_list' => $shopping_list,
            'shopping_list_ids' => $shopping_list_ids,
            'foodItems' => isset($foodItems) ? $foodItems : null
        ]);
    }

    public function addEntry()
    {

        $data = request('entry');
        
        $dt =  Carbon::parse($data['selected']['year']."-".$data['selected']['month']."-".$data['selected']['day']);

        switch ($data['entry_type']) {
            case 'Workout':
                $workout = Workout::find($data['workout_id']);
                $name = $workout->name;
                break;
            case 'Custom Workout':    
                $workout = CustomWorkout::find($data['custom_workout_id']);
                $name = $workout->name;
                break;
            case 'Meal':
                $meal = Meal::find($data['meal_id']);
                $name = $meal->name;
                break;
            case 'Recipe':
                $recipe = Recipe::find($data['recipe_id']);
                $name = $recipe->name;
                break;
        }

        $data['entry_data']['name'] = $name;

        $entry = Entry::create([
            'user_id' => Auth::user()->id,
            'meal_id' => $data['meal_id'] == 0 ? null : $data['meal_id'], 
            'recipe_id' => $data['recipe_id'] == 0 ? null : $data['recipe_id'], 
            'workout_id' => $data['workout_id'] == 0 ? null : $data['workout_id'],
            'custom_workout_id' => $data['custom_workout_id'] == 0 ? null : $data['custom_workout_id'], 
            'date_time' => $dt,
            'entry_type' => $data['entry_type'], 
            'entry_data' => $data['entry_data'], 
        ]);

        return redirect("/planner");

    }

    public function updateEntry()
    {

        $data = request('entry');
        $dt =  Carbon::parse($data['date_time']);
        $data["date_time"] = $dt;

        Arr::forget($data, ['meal', 'recipe', 'workout', 'custom_workout', 'created_at', 'updated_at']);
        
        $entry = Entry::where('id', $data['id'])
                        ->update($data);


        return redirect("/planner");
        
    }
    
    public function destroy(Entry $entry)
    {
        $entry->delete();
        return redirect("/planner");

    }

    public function addToList(FoodItem $fooditem)
    {
        Auth::user()->shopping_list()->attach($fooditem->id);

        return redirect('/planner');
    }
    
    public function removeFromList($shopping_list_id)
    {
        $remove = DB::table('shopping_list')->where('id', $shopping_list_id)->delete();
        return redirect('/planner');
    }


    public function generateList(){

        $user = Auth::user();

        $days = intval(request('days'));

        $today = Carbon::today();
        $endDay = Carbon::today()->addDays($days)->endOfDay();

        $entries = Entry::whereIn('entry_type', ['Meal', 'Recipe'])->whereBetween('date_time', [$today, $endDay])->get();

        // get ingredients for each entry
        $items = collect();
        $entries->map( function($entry) use ($items) {
            if( $entry->entry_type == 'Meal'){
                $entry->meal->ingredients->map( function($ing) use ($items){
                    if (!$items->contains('id', $ing->id)) {
                        $items->push($ing);
                    }
                });
            } else {
                $entry->recipe->ingredients->map( function($ing) use ($items){
                    if (!$items->contains('id', $ing->id)) {
                        $items->push($ing);
                    }
                });
            }
        });

        $ids = $items->pluck('id')->toArray();
        $shopping_list = $user->shopping_list->pluck('id')->toArray();
        $inventory = $user->food_items->pluck('id')->toArray();
        
        $result = array_diff($ids, $shopping_list);
        $result = array_diff($ids, $inventory);

        $user->shopping_list()->attach($result);
        return redirect('/planner');
    }


}
