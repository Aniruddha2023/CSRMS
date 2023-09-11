<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Database\Eloquent\Factories\HasFactory;



class Wishlist extends Model
{
    // use HasFactory;

    // public static function countWishlist($location_id) {
    //     $countWishlist = Wishlist::where(['user_id' => Auth::user()->id, 
    //     'location_id' => $location_id])->count();
    //     return $countWishlist;
    // }

    // protected $table = 'wishlists';

    protected $guarded=[];
}
