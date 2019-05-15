<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Uom extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
