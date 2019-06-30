<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Discount extends Model
{
    use SoftDeletes;

    protected $appends = ['amount_formatted'];

    public function getAmountFormattedAttribute()
    {
        $setting = Setting::getSetting();
        $currency = !empty($setting->currency) ?  $setting->currency : 'Rp';
        $decimal_separator = !empty($setting->decimal_separator) ? $setting->decimal_separator : '.';
        $thousand_separator = !empty($setting->thousand_separator) ? $setting->thousand_separator : ',';

        if ($this->type == 'fixed') {
            return $currency.number_format($this->amount,2,$decimal_separator,$thousand_separator);
        } else {
            return number_format($this->amount,2,$decimal_separator,$thousand_separator).'%';
        }

    }
}
