<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;
    protected $dates = ['created_at'];
    protected $appends = ['evidence_link', 'amount_formatted'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getEvidenceLinkAttribute()
    {
        return url('storage/'.$this->evidence);
    }

    public function getAmountFormattedAttribute()
    {
        return 'Rp'.number_format($this->amount);
    }
}
