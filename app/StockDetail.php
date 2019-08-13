<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class StockDetail extends Model
{
    use SoftDeletes;
    protected $fillable = ['stock_id'];
    public function stock()
    {
        return $this->belongsTo('App\Stock');
    }

    public function sales()
    {
        return $this->belongsTo('App\Sales');
    }

    public function purchase()
    {
        return $this->belongsTo('App\Purchase');
    }
}
