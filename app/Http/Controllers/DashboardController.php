<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Unit;
use Inertia\Inertia;
use App\Models\Entry;
use App\Models\UserMeal;
use App\Models\UserRecipe;
use App\Models\UserFoodItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $dt = Carbon::now();
        $entries = $user->entries->filter( function( $entry ) use ($dt) {
                                            return $entry->date_time <= $dt && $entry->completed == 0;
                                        })
                                        ->map( function( $entry ) use ($dt){
                                            $entry->date_time_formatted = $entry->date_time->format('D, d M Y'); 
                                            if($entry->date_time->format('Ymd') < $dt->format('Ymd')){
                                                $entry->in_past = true;
                                                $entry->date_time_human = $entry->date_time->diffForHumans(); 
                                            } else {
                                                $entry->date_time_human = 'today'; 
                                            }
                                            return $entry;
                                        })
                                        ->sortBy('date_time')->values();

        $locations = $user->locations;

        
        $shopping_list = $user->shopping_list;
        $shopping_list = $shopping_list->sortBy('name')->values();

        return Inertia::render('Dashboard', [
            'entries' => $entries,
            'locations' => $locations,
            'shopping_list' => $shopping_list,
        ]);
    }

    public function complete(Entry $entry)
    {
        $entry->completed = 1;
        $entry->save();

        foreach( request('removeItems') as $id ){
            UserFoodItem::where('food_item_id', $id)->first()->delete();
        }
        
        return redirect('/dashboard');
    }

    public function leftovers(){
        $entry = request('entry');
        $locationId = request('location');
        $qty = request('qty');
        $unit = Unit::where('name', 'portion')->first();

        if( $entry['entry_type'] == 'Meal' ){
            $meal = $entry['meal'];

            UserMeal::create([
                'meal_id' => $meal['id'],
                'user_id' => Auth::user()->id,
                'location_id' => $locationId,
                'qty' => $qty,
                'unit_id' => $unit->id,
                'date_in' => new Carbon,
            ]);
        }
        
        if( $entry['entry_type'] == 'Recipe' ){
            $recipe = $entry['recipe'];
    
            UserRecipe::create([
                'recipe_id' => $recipe['id'],
                'user_id' => Auth::user()->id,
                'location_id' => $locationId,
                'qty' => $qty,
                'unit_id' => $unit->id,
                'date_in' => new Carbon,
            ]);

        }

    }
}
