<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeComment extends Model
{
    use HasFactory;

    protected $table = 'recipe_comments';

    public $timestamps = false;
    
    protected $fillable = [
        'userId',
        'recipeId',
        'comment',
    ];

    protected $casts = [
        'id' => 'integer',
        'userId' => 'integer',
        'recipeId' => 'integer',
        'comment' => 'string',
    ];
    
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'userId');
    }
}
