<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Customer extends Model
{
    use softDeletes;

    protected $customers = ['deleted_at'];
}
