<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class RecipeCategoryResource extends JsonResource
{
    protected $userId;

    public function userId($value)
    {
        $this->userId = $value;
        return $this;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $recipes = DB::table('recipes')
        ->leftjoin('users', 'users.id', '=', 'recipes.userId')
        ->leftjoin('user_followers', 'user_followers.followerId', '=', 'users.id')
        ->where([['recipes.id', '=', $this->recipeId], ['user_followers.userId', '!=', $this->userId]])
        ->select('recipes.*')
        ->first();
        // $recipes2 = DB::table('recipes')->where('userId', '=', $this->userId)->first();
        // $result = $re->merge($recipes2);
        return $recipes;
    }
}
