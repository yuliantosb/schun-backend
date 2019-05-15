<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class StockIn extends Model
{

    //
    use softDeletes;
    protected $dates = ['deleted_at'];

    public function Items() {
        return $this->belongsTo('App\Item', 'item_id', '_id');
    }
}
