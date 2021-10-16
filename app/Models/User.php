<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'image',
        'email',
        'password',
        'usertype',
        'status',
        'instagramUrl',
        'facebookUrl',
        'youtubeUrl',
        'pinterestUrl',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'image' => 'string',
        'authKey' => 'string',
        'device_token' => 'string',
        'email' => 'string',
        'password' => 'string',
        'usertype' => 'integer',
        'status' => 'integer',
        'intagramUrl' => 'string',
        'facebookUrl' => 'string',
        'youtubeUrl' => 'string',
        'pinterestUrl' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string',
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function recipes()
    {
        return $this->hasMany(Recipe::class, 'userId', 'id');
    }

    public function followers()
    {
        return $this->hasMany(UserFollower::class, 'followerId', 'id')->with('user');
    }

    public function following()
    {
        return $this->hasMany(UserFollower::class, 'userId', 'id')->with('user');
    }

    public function pushNotification($title, $body, $message)
    {
        $token = $this->device_token;
        $fcmKey = DB::table('settings')->find(1)->fcm_key;

        if ($token == null) return;

        $data['notification']['title'] = $title;
        $data['notification']['body'] = $body;
        $data['notification']['sound'] = true;
        $data['priority'] = 'normal';
        $data['data']['click_action'] = 'FLUTTER_NOTIFICATION_CLICK';
        $data['data']['message'] = $message;
        $data['to'] = $token;
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $fcmKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
        $success = json_decode($response, true)['success'];
    }
}
