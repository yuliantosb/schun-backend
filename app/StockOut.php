<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class StockOut extends Model
{
    use softDeletes;

    protected $stockout = ['deleted_at'];
}
