<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId',
        'name',
        'image',
        'duration',
        'noOfServing',
        'difficulty',
        'cuisine_id',
        'ingredients',
        'steps',
        'websiteUrl',
        'youtubeUrl',
        'noOfViews',
        'noOfLikes',
        'status',
    ];

    protected $dates = [
        'created_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'userId' => 'integer',
        'name' => 'string',
        'image' => 'string',
        'duration' => 'integer',
        'noOfServing' => 'integer',
        'difficulty_id' => 'integer',
        'cuisine_id' => 'integer',
        'ingredients' => 'string',
        'steps' => 'string',
        'websiteUrl' => 'string',
        'youtubeUrl' => 'string',
        'noOfViews' => 'integer',
        'noOfLikes' => 'integer',
        'status' => 'integer',
        'created_at' => 'string',
        'updated_at' => 'string',
    ];

    public function recipeStatus() {
        return $this->hasOne(Status::class, 'id', 'status');
    }

    public function cuisine() {
        return $this->hasOne(Cuisine::class, 'id', 'cuisine_id');
    }

    public function difficulty() {
        return $this->hasOne(Difficulty::class, 'id', 'difficulty_id');
    }

    public function user() {
        return $this->hasOne(User::class, 'id', 'userId');
    }

    public function recipecategory() {
        return $this->belongsTo(RecipeCategory::class, 'recipeId');
    }

    public function recipecategories() {
        return $this->hasMany(RecipeCategory::class, 'recipeId');
    }

    public function language() {
        $available_locales=config('app.locales');
        foreach($available_locales as $i => $lang) {
            if($i == $this->language_code) 
            return $lang;
        } 
        return '';
    }

  
}
