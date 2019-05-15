<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class StockIn extends Model
{
    use softDeletes;

    protected $stockin = ['deleted_at'];
}
