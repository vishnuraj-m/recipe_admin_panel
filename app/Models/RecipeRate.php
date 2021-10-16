<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeRate extends Model
{
    use HasFactory;

    protected $table="recipe_rates";

    public $timestamps = false;
    
    protected $fillable = [
        'recipeId',
        'userId',
        'rate',
    ];

    protected $casts = [
        'id' => 'integer',
        'recipeId' => 'integer',
        'userId' => 'integer',
        'rate' => 'double',
    ];
    
}
