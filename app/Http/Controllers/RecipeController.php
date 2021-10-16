<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Cuisine;
use App\Models\Difficulty;
use App\Models\Recipe;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class RecipeController extends Controller
{
    public function index(string $condition)
    {
        switch ($condition) {
            case 'all':
                $recipes = Recipe::with(['recipeStatus', 'cuisine', 'user'])
                    ->orderBy('created_at', 'desc')
                    ->get();
                break;
            case 'pending':
                $recipes = Recipe::with(['recipeStatus', 'cuisine', 'user'])
                    ->orderBy('created_at', 'desc')
                    ->where('status', '=', 1)
                    ->get();
                break;
            case 'approved':
                $recipes = Recipe::with(['recipeStatus', 'cuisine', 'user'])
                    ->orderBy('created_at', 'desc')
                    ->where('status', '=', 2)
                    ->get();
                break;
            case 'disapproved':
                $recipes = Recipe::with(['recipeStatus', 'cuisine', 'user'])
                    ->orderBy('created_at', 'desc')
                    ->where('status', '=', 3)
                    ->get();
                break;
            default:
                $recipes = Recipe::with(['recipeStatus', 'cuisine', 'user'])
                    ->where('language_code', $condition)
                    ->orderBy('created_at', 'desc')
                    ->get();
                break;
        }

        $allRecipesCount = Recipe::count();
        $recipeLangs = Recipe::select('language_code', DB::raw('count(*) as total'))
            ->groupBy('language_code')
            ->get();

        return view('recipes.recipes-index', compact('recipes', 'condition', 'allRecipesCount', 'recipeLangs'));
    }

    public function recipeedit(Request $request, $id)
    {
        $recipes = Recipe::findOrFail($id);
        $difficulties = Difficulty::where('language_code', $recipes->language_code)->get();
        $cuisines = Cuisine::where('language_code', $recipes->language_code)->get();
        $categories = Category::where('language_code', $recipes->language_code)->get();
        $statuses = Status::all();
        $selectedCategories = DB::table('recipe_categories')->where('recipeId', $id)->get();
        $available_locales = config('app.locales');
        return view('recipes.recipe-edit', ['difficulties' => $difficulties, 'cuisines' => $cuisines, 'categories' => $categories, 'selectedCategories' => $selectedCategories, 'recipes' => $recipes, 'statuses' => $statuses, 'available_locales' => $available_locales]);
    }

    public function recipeupdate(Request $request, $id)
    {
        $recipe = Recipe::find($id);
        $recipe->name = trim($request->input('name'));
        $recipe->userId = $request->input('userId');
        $recipe->duration = trim($request->input('duration'));
        $recipe->noOfServing = trim($request->input('noOfServing'));
        $recipe->difficulty_id = trim($request->input('difficulty_id'));
        $recipe->cuisine_id = $request->input('cuisine_id');
        $recipe->ingredients = trim($request->input('ingredients'));
        $recipe->steps = trim($request->input('steps'));
        $recipe->websiteUrl = trim($request->input('websiteUrl'));
        $recipe->youtubeUrl = trim($request->input('youtubeUrl'));
        $recipe->status = $request->input('status');
        $categories = $request->input('category');

        if ($request->hasFile('image')) {
            if ($recipe->image != null) {
                $oldImagePath = public_path("/uploads/recipes/$recipe->image");
                unlink($oldImagePath);
            }
            $image =  $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $image->move('uploads/recipes/', $filename);
            $recipe->image = $filename;
        }

        DB::table('recipe_categories')->where('recipeId', '=', $id)->delete();

        $recipe->save();

        $data = [];
        foreach ($categories as $category) {
            $data[] = [
                'recipeId' => $id,
                'categoryId' => $category,
            ];
        }

        DB::table('recipe_categories')->insert($data);

        if ($request->input('status') == 2) {
            $currentUserId = Auth::id();
            $user = User::findOrFail($recipe->userId);
            if ($currentUserId != $recipe->userId) {
                $user->pushNotification('Congratulations', 'Your recipe ' . $recipe->name . ' was approved', ['recipe' => Recipe::where('id', $recipe->id)->with(['user', 'difficulty'])->first()]);
            }
        }

        return redirect('/recipes/all')->with('status', 'Recipe updated successfully');
    }

    public function recipedelete($id)
    {
        $recipes = Recipe::findOrFail($id);
        if ($recipes->image !== null) {
            $oldImagePath = public_path("/uploads/recipes/$recipes->image");
            if (File::exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
        $recipes->delete();

        DB::table('recipe_categories')->where('recipeId', $id)->delete();

        return redirect('/recipes/all')->with('status', 'Recipe deleted successfully');
    }

    public function recipeadd(Request $request)
    {
        $recipe = new Recipe;

        $recipe->language_code = $request->language_code;
        $recipe->name = trim($request->input('name'));
        $recipe->userId = $request->input('userId');
        $recipe->duration = trim($request->input('duration'));
        $recipe->noOfServing = trim($request->input('noOfServing'));
        $recipe->difficulty_id = trim($request->input('difficulty_id'));
        $recipe->cuisine_id = $request->input('cuisine_id');
        $recipe->ingredients = trim($request->input('ingredients'));
        $recipe->steps = trim($request->input('steps'));
        $recipe->websiteUrl = trim($request->input('websiteUrl'));
        $recipe->youtubeUrl = trim($request->input('youtubeUrl'));
        $recipe->status = $request->input('status');
        $categories = $request->input('category');

        if ($request->hasFile('image')) {
            $image =  $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $image->move('uploads/recipes/', $filename);
            $recipe->image = $filename;
        }

        $recipe->save();

        $data = [];
        foreach ($categories as $category) {
            $data[] = [
                'recipeId' => $recipe->id,
                'categoryId' => $category,
            ];
        }

        DB::table('recipe_categories')->insert($data);

        return redirect('/recipes/all')->with('status', 'Recipe added successfully');
    }
}
