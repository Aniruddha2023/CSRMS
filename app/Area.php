<?php

namespace App;

use App\Area;
use App\User;
use App\Order;
use App\Location;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function location(){
        return $this->belongsTo(Location::class);
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    // protected $fillable = [
    //     'user_id','address','location_id','contact','area_size','number_of_toilet','number_of_belcony','description','month','rent','map_link','featured_image','images','video','status'
    // ];

    protected $guarded=[];
}
