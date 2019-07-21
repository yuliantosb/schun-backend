<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class PurchaseDetail extends Model
{
    use SoftDeletes;

    protected $fillable = ['product_id'];
    protected $appends = ['price_formatted', 'subtotal_formatted', 'name'];

    public function product()
    {
        return $this->belongsTo('App\Products', 'product_id');
    }

    public function getNameAttribute()
    {
        return $this->product_name;
    }

    public function getSubtotalFormattedAttribute()
    {
        $setting = Setting::getSetting();
        $currency = !empty($setting->currency) ?  $setting->currency : 'Rp';
        $decimal_separator = !empty($setting->decimal_separator) ? $setting->decimal_separator : '.';
        $thousand_separator = !empty($setting->thousand_separator) ? $setting->thousand_separator : ',';

        return $currency.number_format($this->subtotal,2,$decimal_separator,$thousand_separator);
    }

    public function getPriceFormattedAttribute()
    {
        $setting = Setting::getSetting();
        $currency = !empty($setting->currency) ?  $setting->currency : 'Rp';
        $decimal_separator = !empty($setting->decimal_separator) ? $setting->decimal_separator : '.';
        $thousand_separator = !empty($setting->thousand_separator) ? $setting->thousand_separator : ',';

        return $currency.number_format($this->price,2,$decimal_separator,$thousand_separator);
    }
}
