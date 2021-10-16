<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return User::withCount(['following', 'followers'])->find(Auth::id());
});

Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return $user->createToken($request->device_name)->plainTextToken;
});

Route::middleware('auth:sanctum')->get('/user/revoke', function (Request $request) {
    $user = $request->user();
    $user->currentAccessToken()->delete();
    return 'tokens are deleted';
});

// Route::group(['middleware' => 'auth:sanctum'], function () {

// Route::apiResource('categories', 'App\Http\Controllers\Api\CategoryController');
Route::get('categories/{lang}', 'App\Http\Controllers\Api\CategoryController@index');
Route::get('fetchCategories/{lang}/{offset}',  'App\Http\Controllers\Api\CategoryController@fetchCategories');
Route::get('categories/search/{name}', 'App\Http\Controllers\Api\CategoryController@search');

// Route::apiResource('cuisines', 'App\Http\Controllers\Api\CuisineController');
Route::get('cuisines/{lang}', 'App\Http\Controllers\Api\CuisineController@index');
Route::get('fetchCuisines/{lang}/{offset}',  'App\Http\Controllers\Api\CuisineController@fetchCuisines');
Route::get('cuisines/search/{name}', 'App\Http\Controllers\Api\CuisineController@search');

// Route::apiResource('difficulties', 'App\Http\Controllers\Api\DifficultyController');
Route::get('difficulties/{lang}', 'App\Http\Controllers\Api\DifficultyController@index');

Route::apiResource('recipes', 'App\Http\Controllers\Api\RecipeController');
Route::get('recipes/search/{lang}/{name}', 'App\Http\Controllers\Api\RecipeController@search');
Route::get('fetchRecipesByCuisine/{lang}/{id}/{offset}', 'App\Http\Controllers\Api\RecipeController@fetchRecipesByCuisine');
Route::get('showRecipesByUser/{id}', 'App\Http\Controllers\Api\RecipeController@showRecipesByUser');
Route::get('fetchProfileUserRecipes/{lang}/{id}', 'App\Http\Controllers\Api\RecipeController@fetchProfileUserRecipes');
Route::get('fetchRecipesByCategory/{lang}/{id}/{offset}', 'App\Http\Controllers\Api\RecipeController@fetchRecipesByCategory');
Route::get('fetchMostCollectedRecipes/{lang}', 'App\Http\Controllers\Api\RecipeController@fetchMostCollectedRecipes');
Route::get('fetchRecentRecipes/{lang}/{offset}', 'App\Http\Controllers\Api\RecipeController@fetchRecentRecipes');
Route::put('updateRecipeLikes/{id}/{operation}', 'App\Http\Controllers\Api\RecipeController@updateRecipeLikes');
Route::get('getRecipeLikes/{id}', 'App\Http\Controllers\Api\RecipeController@getRecipeLikes');
Route::post('addRecipeRate/{id}/{userId}/{rate}', 'App\Http\Controllers\Api\RecipeController@addRecipeRate');
Route::get('getRecipeRate/{id}', 'App\Http\Controllers\Api\RecipeController@getRecipeRate');
Route::get('getUserRate/{id}/{userId}', 'App\Http\Controllers\Api\RecipeController@getUserRate');
Route::post('addRecipeComment/{id}/{userId}/{comment}', 'App\Http\Controllers\Api\RecipeController@addRecipeComment');
Route::get('getRecipeComments/{id}', 'App\Http\Controllers\Api\RecipeController@getRecipeComments');
Route::delete('deleteUserComment/{id}', 'App\Http\Controllers\Api\RecipeController@deleteUserComment');
Route::put('updateRecipeView/{id}', 'App\Http\Controllers\Api\RecipeController@updateRecipeView');

Route::apiResource('settings', 'App\Http\Controllers\Api\SettingsController');
Route::get('fetchLanguages', 'App\Http\Controllers\Api\SettingsController@fetchLanguages');

Route::apiResource('users', 'App\Http\Controllers\Api\UserController');
Route::post('loginUsingSocial', 'App\Http\Controllers\Api\UserController@loginUsingSocial');
Route::post('updateEmail', 'App\Http\Controllers\Api\UserController@updateEmail');
Route::get('users/search/{name}/{id}', 'App\Http\Controllers\Api\UserController@search');
Route::delete('deleteImage/{id}', 'App\Http\Controllers\Api\UserController@deleteImage');
Route::put('changePassword/{id}/{oldPassword}/{newPassword}', 'App\Http\Controllers\Api\UserController@changePassword');
Route::post('addUserFollow/{id}/{followerId}', 'App\Http\Controllers\Api\UserController@addUserFollow');
Route::get('getProfileInfo/{lang}/{id}', 'App\Http\Controllers\Api\UserController@getProfileInfo');
Route::get('fetchFollowingFollowers/{id}', 'App\Http\Controllers\Api\UserController@fetchFollowingFollowers');
Route::get('checkIfUserIsFollowing/{id}/{followerId}', 'App\Http\Controllers\Api\UserController@checkIfUserIsFollowing');
Route::post('setDeviceToken/{token}', 'App\Http\Controllers\Api\UserController@setDeviceToken');
Route::post('forgotPassword', 'App\Http\Controllers\Api\UserController@forgotPassword');
Route::get('checkAccountStatus/{id}', 'App\Http\Controllers\Api\UserController@checkAccountStatus');
Route::post('getUserFavorites', 'App\Http\Controllers\Api\UserController@getUserFavorites');
// });

Route::post("login", 'App\Http\Controllers\Api\UserController@index');

Route::post('addDeviceToken/{token}', 'App\Http\Controllers\Api\UserController@addDeviceToken');
