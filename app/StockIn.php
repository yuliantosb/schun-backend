<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class StockIn extends Model
{
    //
    public function Items() {
        return $this->belongsTo('App\Item', 'item_id', '_id');
    }
}
