<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Device extends Model
{
    //
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function images(){
        return $this->hasMany(Image::class);
    }

    public function textSnippets(){
        return $this->hasMany(Image::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'secret',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'secret',
    ];

}
