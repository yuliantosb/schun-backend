<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class StockIn extends Model
{

    use softDeletes;
    protected $dates = ['deleted_at', 'stock_in_date'];
    protected $fillable = ['item_id', 'stock_in_date', 'qty', 'price', 'evidence'];
    protected $appends = ['formatted_price'];

    public function Items() {
        return $this->belongsTo('App\Item', 'item_id', '_id');
    }

    public function getFormattedPriceAttribute() {
        return 'RP.'.number_format($this->price);
    }
}
