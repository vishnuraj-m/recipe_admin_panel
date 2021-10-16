<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Difficulty extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'difficulty',
    ];

    protected $casts = [
        'id' => 'integer',
        'difficulty' => 'string',
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
