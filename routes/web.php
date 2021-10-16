<?php

use App\Models\Category;
use App\Models\Cuisine;
use App\Models\Difficulty;
use App\Models\Status;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
  return view('welcome');
});


Auth::routes();

// Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::group(['middleware' => ['auth', 'admin']], function () {

  Route::get('/', 'App\Http\Controllers\HomeController@index')->name('dashboard');

  Route::get('/dashboard', 'App\Http\Controllers\HomeController@index')->name('dashboard');

  Route::get('/user-add', function () {
    return view('users.user-add');
  })->name('upgrade');

  Route::get('/category-add', function () {
    $available_locales = config('app.locales');
    return view('categories.category-add', compact('available_locales'));
  })->name('upgrade');

  Route::get('/difficulty-add', function () {
    $available_locales = config('app.locales');
    return view('difficulties.difficulty-add', compact('available_locales'));
  })->name('difficulty');


  Route::get('/checkout-add', function () {
    return view('checkouts.checkout-add');
  })->name('checkout');

  Route::get('/cuisine-add', function () {
    $available_locales = config('app.locales');
    return view('cuisines.cuisine-add', compact('available_locales'));
  })->name('cuisine');

  Route::get('/status-add', function () {
    return view('recipes.recipe-status-add');
  })->name('recipe');

  Route::get('/notification-add', function () {
    return view('notifications.notification-add');
  })->name('notification');

  Route::get('/recipe-add', function () {
    $statuses = Status::all();
    $available_locales = config('app.locales');
    return view('recipes.recipe-add', ['statuses' => $statuses, 'available_locales' => $available_locales]);
  })->name('recipe');

  Route::post('/reset-password', [
    'uses' => 'Auth\ResetPasswordController@reset',
    'as' => 'reset'
  ]);

  Route::get('notification/update/', 'App\Http\Controllers\PanelNotificationController@changeNotificationState');

  Route::resource('users', 'App\Http\Controllers\UserController', ['except' => ['show']]);
  Route::get('/user-edit/{id}', 'App\Http\Controllers\UserController@useredit');
  Route::get('/user-delete/{id}', 'App\Http\Controllers\UserController@userdelete');
  Route::put('/user-update/{id}', 'App\Http\Controllers\UserController@userupdate');
  Route::put('/user-add', 'App\Http\Controllers\UserController@useradd');

  Route::resource('categories', 'App\Http\Controllers\CategoryController', ['except' => ['show']]);
  Route::get('/categories/{condition}', 'App\Http\Controllers\CategoryController@index');
  Route::get('/category-edit/{id}', 'App\Http\Controllers\CategoryController@categoryedit');
  Route::get('/category-delete/{id}', 'App\Http\Controllers\CategoryController@categorydelete');
  Route::put('/category-update/{id}', 'App\Http\Controllers\CategoryController@categoryupdate');
  Route::put('/category-add', 'App\Http\Controllers\CategoryController@categoryadd');
  Route::get('/getCategories/{id}', 'App\Http\Controllers\CategoryController@getCategories');

  Route::resource('difficulties', 'App\Http\Controllers\DifficultyController', ['except' => ['show']]);
  Route::get('/difficulties/{condition}', 'App\Http\Controllers\DifficultyController@index');
  Route::get('/difficulty-edit/{id}', 'App\Http\Controllers\DifficultyController@difficultyedit');
  Route::get('/difficulty-delete/{id}', 'App\Http\Controllers\DifficultyController@difficultydelete');
  Route::put('/difficulty-update/{id}', 'App\Http\Controllers\DifficultyController@difficultyupdate');
  Route::put('/difficulty-add', 'App\Http\Controllers\DifficultyController@difficultyadd');
  Route::get('/getDifficulties/{id}', 'App\Http\Controllers\DifficultyController@getDifficulties');

  Route::resource('cuisines', 'App\Http\Controllers\CuisineController', ['except' => ['show']]);
  Route::get('/cuisines/{condition}', 'App\Http\Controllers\CuisineController@index');
  Route::get('/cuisine-edit/{id}', 'App\Http\Controllers\CuisineController@cuisineedit');
  Route::get('/cuisine-delete/{id}', 'App\Http\Controllers\CuisineController@cuisinedelete');
  Route::put('/cuisine-update/{id}', 'App\Http\Controllers\CuisineController@cuisineupdate');
  Route::put('/cuisine-add', 'App\Http\Controllers\CuisineController@cuisineadd');
  Route::get('/getCuisines/{id}', 'App\Http\Controllers\CuisineController@getCuisines');

  Route::resource('recipes', 'App\Http\Controllers\RecipeController', ['except' => ['show']]);
  Route::get('/recipes/{condition}', 'App\Http\Controllers\RecipeController@index');
  Route::get('/recipe-edit/{id}', 'App\Http\Controllers\RecipeController@recipeedit');
  Route::get('/recipe-delete/{id}', 'App\Http\Controllers\RecipeController@recipedelete');
  Route::put('/recipe-update/{id}', 'App\Http\Controllers\RecipeController@recipeupdate');
  Route::put('/recipe-add', 'App\Http\Controllers\RecipeController@recipeadd');

  Route::resource('statuses', 'App\Http\Controllers\StatusController', ['except' => ['show']]);
  Route::get('/status-edit/{id}', 'App\Http\Controllers\StatusController@statusedit');
  Route::get('/status-delete/{id}', 'App\Http\Controllers\StatusController@statusdelete');
  Route::put('/status-update/{id}', 'App\Http\Controllers\StatusController@statusupdate');
  Route::put('/status-add', 'App\Http\Controllers\StatusController@statusadd');

  Route::resource('notifications', 'App\Http\Controllers\NotificationController', ['except' => ['show']]);
  Route::get('/notification-edit/{id}', 'App\Http\Controllers\NotificationController@notificationedit');
  Route::get('/notification-delete/{id}', 'App\Http\Controllers\NotificationController@notificationdelete');
  Route::put('/notification-update/{id}', 'App\Http\Controllers\NotificationController@notificationupdate');
  Route::put('/notification-add', 'App\Http\Controllers\NotificationController@notificationadd');
  Route::post('/bulksend/{id}', 'App\Http\Controllers\NotificationController@bulksend')->name('bulksend');

  Route::resource('settings', 'App\Http\Controllers\SettingsController', ['except' => ['show']]);
  Route::put('/settings-update', 'App\Http\Controllers\SettingsController@settingsupdate');

  Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
  Route::put('/profile-update', 'App\Http\Controllers\ProfileController@updateProfile');
  Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
});
