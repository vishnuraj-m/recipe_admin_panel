<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\RecipeResource;
use App\Models\Category;
use App\Models\Recipe;
use App\Models\RecipeCategory;
use App\Models\RecipeComment;
use App\Models\RecipeRate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $recipes = Recipe::where('status', '=', 2)->with(['user', 'difficulty', 'cuisine'])->get();
        return response()->json($recipes, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'userId' => 'required',
            'name' => 'required',
            'duration' => 'required|numeric',
            'noOfServing' => 'required|numeric',
            'difficulty_id' => 'required',
            'ingredients' => 'required',
            'steps' => 'required',
            'language_code' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $recipe = new Recipe;
        $recipe->language_code = $request->language_code;
        $recipe->name = trim($request->input('name'));
        $recipe->userId = $request->input('userId');
        $recipe->duration = trim($request->input('duration'));
        $recipe->noOfServing = trim($request->input('noOfServing'));
        $recipe->difficulty_id = trim($request->input('difficulty_id'));
        if ($request->input('cuisine_id') != 0)
            $recipe->cuisine_id = $request->input('cuisine_id');
        $recipe->ingredients = trim($request->input('ingredients'));
        $recipe->steps = trim($request->input('steps'));
        $recipe->websiteUrl = trim($request->input('websiteUrl'));
        $recipe->youtubeUrl = trim($request->input('youtubeUrl'));
        $categories = json_decode($request->input('categories'));

        $autoApproveState = DB::table('settings')->where('id', 1)->first()->auto_approve;
        if ($autoApproveState == 0) {
            $recipe->status = 1;
        } else {
            $recipe->status = 2;
        }

        if ($request->hasFile('image')) {
            $image =  $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $image->move('uploads/recipes/', $filename);
            $recipe->image = $filename;
        }

        // $recipe = Recipe::create($recipe->all());
        $recipe->save();

        $data = [];
        foreach ($categories as $category) {
            $data[] = [
                'recipeId' => $recipe->id,
                'categoryId' => $category,
            ];
        }
        DB::table('recipe_categories')->insert($data);

        DB::table('panel_notifications')->insert(["message" => "$recipe->name was published", "url" => url('') . "/recipe-edit/$recipe->id"]);

        return response()->json(["code" => "201", "message" => "Recipe added successfully!", "recipe" => Recipe::find($recipe->id)], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $recipecategories = RecipeCategory::where('recipeId', '=', $id)->get();

        $categories = [];
        foreach ($recipecategories as $category) {
            $categories[] = Category::find($category->categoryId);
        }

        $recipe = Recipe::where('id', $id)->with(['user', 'difficulty', 'cuisine'])->first();
        $recipe->categories = $categories;

        if (is_null($recipe)) {
            return response()->json(["message" => "Record not found!"], 404);
        }
        return response()->json($recipe, 200);
    }

    public function showRecipesByUser($id)
    {
        $recipes = Recipe::where([['userId', '=', $id], ['recipes.status', '=', 2]])->orWhere([['userId', '=', $id], ['recipes.status', '=', 1]])->orderBy('id', 'DESC')->with(['user', 'difficulty', 'cuisine'])->paginate(15);

        if (is_null($recipes)) {
            return response()->json(["message" => "Record not found!"], 404);
        }
        return response()->json($recipes, 200);
    }

    public function fetchProfileUserRecipes($lang, $id)
    {
        $recipes = Recipe::where([['userId', '=', $id], ['status', '=', 2], ['language_code', $lang]])->orderBy('id', 'DESC')->with(['user', 'difficulty', 'cuisine'])->paginate(15);

        if (is_null($recipes)) {
            return response()->json(["message" => "Record not found!"], 404);
        }
        return response()->json($recipes, 200);
    }

    public function fetchRecipesByCuisine($lang, $id, $offset)
    {
        $recipes = Recipe::leftJoin('users', 'users.id', '=', 'recipes.userId')
            ->where([['language_code', '=', $lang], ['recipes.cuisine_id', '=', $id], ['recipes.status', '=', 2]])
            ->select('recipes.*')
            ->orderBy('recipes.name')
            ->with(['user', 'difficulty', 'cuisine'])
            ->paginate($offset);

        $recipe = Recipe::where('cuisine_id', '=', $id)->paginate(15);
        if (is_null($recipe)) {
            return response()->json(["message" => "Record not found!"], 404);
        }
        return response()->json($recipes, 200);
    }

    public function fetchRecipesByCategory($lang, $id, $offset)
    {
        $recipes = Recipe::join('recipe_categories', 'recipe_categories.recipeId', '=', 'recipes.id')
            ->leftJoin('users', 'users.id', '=', 'recipes.userId')
            ->where([['language_code', '=', $lang], ['categoryId', '=', $id], ['recipes.status', '=', 2]])
            ->select('recipes.*')
            ->orderBy('recipes.name')
            ->with(['user', 'difficulty', 'cuisine'])
            ->paginate($offset);

        if (is_null($recipes)) {
            return response()->json(["message" => "Record not found!"], 404);
        }
        return response()->json($recipes, 200);
    }

    public function fetchMostCollectedRecipes($lang)
    {
        $recipes = Recipe::where([['language_code', '=', $lang], ['status', '=', 2]])->orderBy('noOfViews', 'DESC')->limit(5)->with(['user', 'difficulty', 'cuisine'])->get();
        if (is_null($recipes)) {
            return response()->json(["message" => "Record not found!"], 404);
        }
        return response()->json($recipes, 200);
    }

    public function fetchRecentRecipes($lang, $offset)
    {
        $recipes = Recipe::where([['language_code', '=', $lang], ['created_at', '>=', Carbon::now()->subMonth()], ['status', '=', 2]])->orderBy('id', 'DESC')->with(['user', 'difficulty', 'cuisine'])->paginate($offset);
        if (is_null($recipes)) {
            return response()->json(["message" => "Record not found!"], 404);
        }
        return response()->json($recipes, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $recipe = Recipe::find($id);
        $recipe->language_code = $request->language_code;
        $recipe->name = trim($request->input('name'));
        $recipe->userId = $request->input('userId');
        $recipe->duration = trim($request->input('duration'));
        $recipe->noOfServing = trim($request->input('noOfServing'));
        $recipe->difficulty_id = trim($request->input('difficulty_id'));
        // if ($request->input('cuisine_id') != null)
        $recipe->cuisine_id = $request->input('cuisine_id') != 'null' ? $request->input('cuisine_id') : null;
        // else {
        //     $recipe->cuisine_id = null;
        // }
        $recipe->ingredients = trim($request->input('ingredients'));
        $recipe->steps = trim($request->input('steps'));
        $recipe->websiteUrl = trim($request->input('websiteUrl'));
        $recipe->youtubeUrl = trim($request->input('youtubeUrl'));
        // $categories = json_decode($request->input('categories'));
        $categories = collect(json_decode($request->input('categories'), true));


        if ($request->hasFile('image')) {
            if ($recipe->image !== null) {
                $oldImagePath = public_path("/uploads/recipes/$recipe->image");
                if (File::exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $image =  $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $image->move('uploads/recipes/', $filename);
            $recipe->image = $filename;
        }

        // DB::table('recipe_categories')->where('recipeId', '=', $id)->delete();

        // $data = [];
        // foreach ($categories as $category) {
        //     $data[] = [
        //         'recipeId' => $id,
        //         'categoryId' => $category,
        //     ];
        // }
        // DB::table('recipe_categories')->insert($data);


        $requested_ids = $categories->toArray();
        $existingCategories = RecipeCategory::where('recipeId', $id)->pluck('categoryId')->toArray();
        $deletableIds = array_diff($existingCategories, $requested_ids);

        if (!is_null($deletableIds)) {
            foreach ($deletableIds as $deletableId) {
                RecipeCategory::where([['recipeId', $id], ['categoryId', $deletableId]])->delete();
            }
        }

        foreach ($categories as $key => $value) {

            $existingOption = RecipeCategory::where([['recipeId', $id], ['categoryId', $categories[$key]]])->first();
            if ($existingOption) {
                // return response()->json('updating question options', 201);    
                $existingOption->recipeId = $id;
                $existingOption->categoryId = $categories[$key];
                $existingOption->save();
            } else {
                $newoption = new RecipeCategory;
                $newoption->recipeId = $id;
                $newoption->categoryId = $categories[$key];
                $newoption->save();
            }
        }


        $recipe->update();


        DB::table('panel_notifications')->insert(["message" => "$recipe->name was updated", "url" => url('') . "/recipe-edit/$recipe->id"]);

        return response()->json(["code" => "200", "message" => "Recipe updated successfully!"], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $recipe = Recipe::find($id);

        if (is_null($recipe)) {
            return response()->json(["message" => "Record not found!"], 404);
        }

        if ($recipe->image !== null) {
            $oldImagePath = public_path("/uploads/recipes/$recipe->image");
            if (File::exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        $recipe->delete();

        DB::table('recipe_categories')->where('recipeId', $id)->delete();
        return response()->json(["message" => "Recipe deleted successfully!"], 204);
    }

    public function updateRecipeLikes($id, $operation)
    {
        $recipe = Recipe::find($id);
        if ($operation == 'plus')
            $recipe->update(['noOfLikes' => $recipe->noOfLikes + 1]);
        else
            $recipe->update(['noOfLikes' => $recipe->noOfLikes - 1]);

        return response()->json(["message" => "No. of Likes updated successfully!"], 200);
    }

    public function getRecipeLikes($id)
    {
        $recipe = Recipe::find($id);
        return response()->json(["likes" => $recipe->noOfLikes], 200);
    }

    public function addRecipeRate($id, $userId, $rate)
    {
        $reciperate = RecipeRate::where([['recipeId', '=', $id], ['userId', '=', $userId]])->first();

        if (is_null($reciperate)) {
            DB::table('recipe_rates')->insert(['recipeId' =>  $id, 'userId' => $userId, 'rate' => $rate]);
        } else {
            DB::table('recipe_rates')->where([['recipeId', '=', $id], ['userId', '=', $userId]])->update(['rate' => $rate]);
        }
        return response()->json(["rated" => true], 201);
    }

    public function addRecipeComment($id, $userId, $comment)
    {
        DB::table('recipe_comments')->insert(['recipeId' =>  $id, 'userId' => $userId, 'comment' => $comment]);
        return response()->json(["message" => "Comment added successfully"], 201);
    }

    public function getRecipeRate($id)
    {
        $recipeRate = DB::table('recipe_rates')->where([['recipeId', '=', $id]])->count();
        $recipeRateSum = DB::table('recipe_rates')->where([['recipeId', '=', $id]])->get()->sum('rate');
        $rate = sprintf("%.1f", ($recipeRateSum / $recipeRate));
        return response()->json(["rate" => $rate], 200);
    }

    public function getUserRate($id, $userId)
    {
        $rate = DB::table('recipe_rates')->where([['recipeId', '=', $id], ['userId', '=', $userId]])->first();
        return response()->json(["rate" => "$rate->rate"], 200);
    }

    public function getRecipeComments($id)
    {
        $comments =  RecipeComment::where('recipeId', $id)->with(['user'])->get();
        return response()->json($comments, 200);
    }

    public function deleteUserComment($id)
    {
        DB::table('recipe_comments')->delete($id);
        return response()->json(["message" => "Comment deleted successfully"], 404);
    }

    public function updateRecipeView($id)
    {
        $recipe = Recipe::find($id);
        $recipe->update(['noOfViews' => $recipe->noOfViews + 1]);
        return response()->json(["message" => "Recipe Views Updated successfully"], 200);
    }

    public function search($lang, $name)
    {
        $recipe = Recipe::where([['language_code', '=', $lang], ['name', "like", "%" . $name . "%"]])->with(['user', 'difficulty', 'cuisine'])->get();
        if (is_null($recipe)) {
            return response()->json(["message" => "No Recipe found!"], 404);
        }
        return response()->json($recipe, 200);
    }
}
