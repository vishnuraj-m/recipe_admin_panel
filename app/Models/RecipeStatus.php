<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeStatus extends Model
{
    use HasFactory;

    protected $table = 'recipe_statuses';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'color',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'color' => 'string',
    ];

}
