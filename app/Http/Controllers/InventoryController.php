<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Unit;
use Inertia\Inertia;
use App\Models\FoodItem;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {
        $locations = Location::with('items')->where('user_id', Auth::user()->id)->get();
        $units = Unit::all();


        if( request()->search){
            $search = request()->search;
            $foodItems = FoodItem::where('name', 'LIKE', "%$search%")->get();
        }
        
        return Inertia::render('Inventory')->with([
            'locations' => $locations,
            'units' => $units,
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
    
    public function removeFoodItem(Location $location, FoodItem $item)
    {   
        $location->items()->detach([$item->id]);
        return redirect('/inventory');
    }


}
