<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $table = "settings";

    public $timestamps = false;

    protected $fillable = [
        'id',
        'fcm_key',
        'auto_approve',
        'privacy_policy',
        'terms_and_conditions',
    ];

    protected $casts = [
        'id' => 'integer',
        'fcm_key' => 'string',
        'auto_approve' => 'integer',
        'privacy_policy' => 'string',
        'terms_and_conditions' => 'string',
    ];

    protected $append = [
        'languages'
    ];

    public function languages() {
        return config('app.locales');
    }

    public function allLanguages()
    {
        return $this->languages();
    }
}
