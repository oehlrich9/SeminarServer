<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TextSnippet extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function device(){
        return $this->belongsTo(Device::class);
    }
}
