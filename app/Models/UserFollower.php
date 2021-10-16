<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFollower extends Model
{
    use HasFactory;

    protected $table = 'user_followers';

    protected $fillable = [
        'userId',
        'followerId',
    ];

    protected $casts = [
        'userId' => 'integer',
        'followerId' => 'integer',
    ];

    protected $appends = ['user'];

    public function getUserAttribute()
    {
        return $this->user();
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'userId');
    }

    public function follower()
    {
        return $this->hasOne(User::class, 'id', 'followerId');
    }
}
