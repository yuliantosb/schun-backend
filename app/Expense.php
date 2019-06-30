<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use App\Setting;

class Expense extends Model
{
    use SoftDeletes;
    protected $dates = ['created_at'];
    protected $appends = ['amount_formatted'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getAmountFormattedAttribute()
    {
        $setting = Setting::getSetting();
        $currency = !empty($setting->currency) ?  $setting->currency : 'Rp';
        $decimal_separator = !empty($setting->decimal_separator) ? $setting->decimal_separator : '.';
        $thousand_separator = !empty($setting->thousand_separator) ? $setting->thousand_separator : ',';

        return $currency.number_format($this->amount,2,$decimal_separator,$thousand_separator);
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
