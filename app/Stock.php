<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Stock extends Model
{
    use SoftDeletes;
    protected $appends = ['amount_formatted'];
    protected $casts = [
        'amount' => 'integer',
    ];

    public static function boot() {

        parent::boot();
        
        static::updating(function($table)  {
            $table->updated_by = auth()->user()->id;
        });

        static::saving(function($table)  {
            $table->created_by = auth()->user()->id;
        });
    }

    public function product()
    {
        return $this->belongsTo('App\Products', 'products_id', '_id');
    }

    public function details()
    {
        return $this->embedsMany('App\StockDetail', 'details');
    }

    public function getAmountFormattedAttribute()
    {
        $decimal_separator = !empty($setting->decimal_separator) ? $setting->decimal_separator : '.';
        $thousand_separator = !empty($setting->thousand_separator) ? $setting->thousand_separator : ',';

        return number_format($this->amount,2,$decimal_separator,$thousand_separator);
    }    
}
