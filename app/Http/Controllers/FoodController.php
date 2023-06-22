<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\FoodItem;
use App\Models\FoodType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index() {
        $food_types = FoodType::all();
        $items = FoodItem::orderBy('name')->get();

        return Inertia::render('Food')->with([
            'fooditems' => $items,
            'foodtypes' => $food_types,
        ]);
    }

    public function save()
    {
        $addItem = FoodItem::create([
            'name' => request('name'),
            'slug' => Str::slug(request('name')),
            'food_type_id' => request('food_type_id'),  
        ]);

        return redirect("/food");
    }
}
