<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Sales extends Model
{
    use SoftDeletes;

    protected $appends = ['subtotal_formatted', 'tax_formatted', 'discount_formatted', 'total_formatted'];

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function details()
    {
        return $this->embedsMany('App\Sales', 'details');
    }

    public function getSubtotalFormattedAttribute()
    {
        $setting = Setting::getSetting();
        $currency = !empty($setting->currency) ?  $setting->currency : 'Rp';
        $decimal_separator = !empty($setting->decimal_separator) ? $setting->decimal_separator : '.';
        $thousand_separator = !empty($setting->thousand_separator) ? $setting->thousand_separator : ',';

        return $currency.number_format($this->subtotal,2,$decimal_separator,$thousand_separator);
    }

    public function getTaxFormattedAttribute()
    {
        $setting = Setting::getSetting();
        $currency = !empty($setting->currency) ?  $setting->currency : 'Rp';
        $decimal_separator = !empty($setting->decimal_separator) ? $setting->decimal_separator : '.';
        $thousand_separator = !empty($setting->thousand_separator) ? $setting->thousand_separator : ',';

        return $currency.number_format($this->tax,2,$decimal_separator,$thousand_separator);
    }

    public function getDiscountFormattedAttribute()
    {
        $setting = Setting::getSetting();
        $currency = !empty($setting->currency) ?  $setting->currency : 'Rp';
        $decimal_separator = !empty($setting->decimal_separator) ? $setting->decimal_separator : '.';
        $thousand_separator = !empty($setting->thousand_separator) ? $setting->thousand_separator : ',';

        return $currency.number_format($this->discount,2,$decimal_separator,$thousand_separator);
    }

    public function getTotalFormattedAttribute()
    {
        $setting = Setting::getSetting();
        $currency = !empty($setting->currency) ?  $setting->currency : 'Rp';
        $decimal_separator = !empty($setting->decimal_separator) ? $setting->decimal_separator : '.';
        $thousand_separator = !empty($setting->thousand_separator) ? $setting->thousand_separator : ',';

        return $currency.number_format($this->total,2,$decimal_separator,$thousand_separator);
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
}
