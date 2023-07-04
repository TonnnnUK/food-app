<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Unit;
use Inertia\Inertia;
use App\Models\FoodItem;
use App\Models\FoodType;
use App\Models\Location;
use App\Models\UserMeal;
use App\Models\UserRecipe;
use App\Models\UserFoodItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {

        $user = Auth::user();

        $locations = Location::with(['items' => function($query){
            return $query->orderBy('name');
        },'meals' => function($query){
            return $query->orderBy('name');
        }, 'recipes' => function($query){
            return $query->orderBy('name');
        }])->where('user_id', $user->id)->get();

        foreach( $locations as $location ){
            foreach( $location->items as $item ){
                $user_food_item = UserFoodItem::find($item->pivot->id);
                $item->pivot->days_old = $user_food_item->days_old;
            }
            foreach( $location->meals as $item ){
                $user_meal = UserMeal::find($item->pivot->id);
                $item->pivot->days_old = $user_meal->days_old;
            }
            foreach( $location->recipes as $item ){
                $user_recipe = UserRecipe::find($item->pivot->id);
                $item->pivot->days_old = $user_recipe->days_old;
            }

        }

        $units = Unit::all();
        $food_types = FoodType::all();
        $shopping_list = $user->shopping_list;


        if( request()->search){
            $search = request()->search;
            $foodItems = FoodItem::where('name', 'LIKE', "%$search%")->get();
        }

        if( request()->type){
            $type_id = request()->type;

            if($type_id == 'shopping_list'){
                $foodItems = $user->shopping_list;
            } else {
                $foodItems = FoodItem::where('food_type_id', $type_id)->get();
            }

        }


        return Inertia::render('Inventory')->with([
            'locations' => $locations,
            'units' => $units,
            'food_types' => $food_types,
            'shopping_list' => $shopping_list,
            'foodItems' => isset($foodItems) ? $foodItems : null,
        ]);
        
    }


    public function addLocation(){

        $addLocation = Location::create([
            'name' => request('name'),
            'user_id' => Auth::user()->id,
        ]);

        return redirect('/inventory');
        
    }
    
    public function findFoodItems(){
        
        $search = request()->search;
        $items = FoodItem::where('name', 'LIKE', "%$search%")->get();

        return Inertia::render('Inventory')->with([
            'locations' => $locations
        ]);

    }


    public function addFoodItems(){

        $foodIDs = request()->items;
        $locationID = request()->location;
        $dt = new Carbon();

        Auth::user()->food_items()->attach($foodIDs, [
            'location_id' => $locationID,
            'date_in' => $dt
        ]);

        return redirect('/inventory');
    }
    
    public function removeFoodItem()
    {   
        $type = request('type');
        $id = request('id');
        
        if ( $type == 'item' ){
            $remove_item = DB::table('user_food_items')->where('id', $id)->delete();
        }

        if ( $type == 'meal' ){
            $remove_item = DB::table('user_meals')->where('id', $id)->delete();
        }

        if ( $type == 'recipe' ){
            $remove_item = DB::table('user_recipes')->where('id', $id)->delete();
        }

        return redirect('/inventory');
    }


}
