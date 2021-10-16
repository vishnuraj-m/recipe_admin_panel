<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuisine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'image' => 'string',
        'status' => 'integer',
        'created_at' => 'string',
        'updated_at' => 'string',
    ];

    public function language() {
        $available_locales=config('app.locales');
        foreach($available_locales as $i => $lang) {
            if($i == $this->language_code) 
            return $lang;
        } 
        return '';
    }

}
