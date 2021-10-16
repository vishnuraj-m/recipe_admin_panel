<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeCategory extends Model
{
    use HasFactory;

    protected $table="recipe_categories";

    public $timestamps = false;
    
    protected $fillable = [
        'recipeId',
        'catgoryId',
    ];

    protected $casts = [
        'id' => 'integer',
        'recipeId' => 'integer',
        'categoryId' => 'integer',
    ];

    public function category() {
        return $this->belongsTo(Category::class, 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'id');
    }
    
    
}
