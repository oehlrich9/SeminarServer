<?php

namespace App;

use Laravel\Passport\HasApiTokens;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Device;
use App\Models\Image;
use App\Models\TextSnippet;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function devices(){
        return $this->hasMany(Device::class);
    }

    public function deviceToken(){
        return $this->hasMany(Device::class);
    }

    public function images(){
        return $this->hasMany(Image::class);
    }

    public function textSnippets(){
        return $this->hasMany(TextSnippet::class);
    }
}
