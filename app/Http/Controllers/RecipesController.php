<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Inertia\Inertia;
use App\Models\Recipe;
use App\Models\FoodItem;
use App\Models\FoodType;
use App\Models\RecipeTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipesController extends Controller
{
    public function index(){

        $recipes = Recipe::all();
        $tags = RecipeTag::all();

        $food_item_tags = [];
        $food_item_tags['chicken-breast'] = 'Chicken Breast';
        $food_item_tags['chicken-thighs'] = 'Chicken Thighs';
        $food_item_tags['mince-beef'] = 'Mince Beef';
        $food_item_tags['pork-steaks'] = 'Pork Steaks';
        $food_item_tags['diced-beef'] = 'Diced Beef';
        $food_item_tags['sirloin-steak'] = 'Sirloin Steak';

        return Inertia::render('Recipes')->with([
            'recipes' => $recipes,
            'tags' => $tags,
            'food_item_tags' => $food_item_tags,
        ]);

    }

    public function save(){

        $addRecipe = Recipe::create([
            'name' => request('name'),
            'servings' => request('servings')
        ]);

        return redirect("/recipe/$addRecipe->id");
    }

    public function show(Recipe $recipe)
    {

        $units = Unit::all();
        $food_types = FoodType::all();
        $tags = RecipeTag::all();
        $fetchTags = [];
        
        if( request()->type){
            $foodItems = FoodItem::where('food_type_id', request()->type)->orderBy('name')->get();
        }
        
        if( request()->tag){
            $search = request()->tag;
            $fetchTags = RecipeTag::where('tag_name', 'LIKE', "%$search%")->get();
        }
        
        return Inertia::render('Recipe')->with([
            'recipe' => $recipe,
            'units' => $units,
            'foodItems' => isset($foodItems) ? $foodItems : null,
            'foodTypes' => $food_types,
            'tags' => $tags,
            'fetchTags' => $fetchTags
        ]);
        
    }
    
    public function edit(Recipe $recipe)
    {

        if( Auth::user() == null || Auth::user()->email != 'a.hutchinson86@gmail.com') {
            abort(403);
        }
        
        $units = Unit::all();
        $food_types = FoodType::all();
        $tags = RecipeTag::all();
        $fetchTags = [];
        
        if( request()->type){
            $foodItems = FoodItem::where('food_type_id', request()->type)->orderBy('name')->get();
        }
        
        if( request()->tag){
            $search = request()->tag;
            $fetchTags = RecipeTag::where('tag_name', 'LIKE', "%$search%")->get();
        }
        
        return Inertia::render('EditRecipe')->with([
            'recipe' => $recipe,
            'units' => $units,
            'foodItems' => isset($foodItems) ? $foodItems : null,
            'foodTypes' => $food_types,
            'tags' => $tags,
            'fetchTags' => $fetchTags
        ]);
        
    }

    public function addIngredient(Recipe $recipe){

        $recipe->ingredients()->attach([request('id')], [
            'food_item_id' => request('id'),
            'unit_id' => request('unitID'),
            'recipe_id' => $recipe->id,
            'qty' => request('qty'),
        ]);

        return redirect("/recipe/$recipe->id/edit");
        
    }

    public function removeIngredient(Recipe $recipe, FoodItem $fooditem){

        $recipe->ingredients()->detach([$fooditem->id]);

        return redirect("/recipe/$recipe->id/edit");
    }
}
