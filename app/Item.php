<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Item extends Model
{
    use softDeletes;
    protected $dates = ['deleted_at'];
       
    public function Uom() {
        return $this->belongsTo('App\Uom');
    }

    public function StockIn() {
        return $this->hasMany('App\StockIn', 'item_id', '_id');
    }

}
