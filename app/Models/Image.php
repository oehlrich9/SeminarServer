<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'analized', 'emotion', 'dateTimeTaken',
    ];

    protected $hidden = [
        'path',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function device(){
        return $this->belongsTo(Device::class);
    }

    public function emotion()
    {
        return $this->hasOne(Emotions::class);
    }
}
