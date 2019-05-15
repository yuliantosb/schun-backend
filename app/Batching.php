<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Batching extends Model
{
    use softDeletes;

    protected $dates = ['deleted_at'];
}
