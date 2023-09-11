<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Area;

class Order extends Model
{
    public function house(){
        return $this->belongsTo(Area::class);
    }
    
    protected $fillable = [
        'user_id', 'location_id', 'amount', 'status', 'transaction_id', 'currency'
    ];
}
