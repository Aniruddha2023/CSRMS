<?php

namespace App;

use App\Area;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public function houses(){
        return $this->hasMany(Area::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    protected $guarded=[];
}
