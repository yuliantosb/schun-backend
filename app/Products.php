<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Products extends Model
{
    use SoftDeletes;

    protected $appends = ['price_formatted', 'wholesale_formatted', 'cost_formatted'];
    protected $fillable = ['stock'];

    public function Category()
    {
        return $this->belongsTo('App\Category');
    }

    public function Stock()
    {
        return $this->hasOne('App\Stock');
    }

    public static function boot() {

        parent::boot();
        
        static::updating(function($table)  {
            $table->updated_by = auth()->user()->id;
        });

        static::saving(function($table)  {
            $table->created_by = auth()->user()->id;
        });
    }

    public function getPriceFormattedAttribute()
    {
        $setting = Setting::getSetting();
        $currency = !empty($setting->currency) ?  $setting->currency : 'Rp';
        $decimal_separator = !empty($setting->decimal_separator) ? $setting->decimal_separator : '.';
        $thousand_separator = !empty($setting->thousand_separator) ? $setting->thousand_separator : ',';

        return $currency.number_format($this->price,2,$decimal_separator,$thousand_separator);
    }

    public function getWholesaleFormattedAttribute()
    {
        $setting = Setting::getSetting();
        $currency = !empty($setting->currency) ?  $setting->currency : 'Rp';
        $decimal_separator = !empty($setting->decimal_separator) ? $setting->decimal_separator : '.';
        $thousand_separator = !empty($setting->thousand_separator) ? $setting->thousand_separator : ',';

        return $currency.number_format($this->wholesale,2,$decimal_separator,$thousand_separator);
    }

    public function getCostFormattedAttribute()
    {
        $setting = Setting::getSetting();
        $currency = !empty($setting->currency) ?  $setting->currency : 'Rp';
        $decimal_separator = !empty($setting->decimal_separator) ? $setting->decimal_separator : '.';
        $thousand_separator = !empty($setting->thousand_separator) ? $setting->thousand_separator : ',';

        return $currency.number_format($this->cost,2,$decimal_separator,$thousand_separator);
    }
}
