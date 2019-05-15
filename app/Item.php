<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Item extends Model
{
    use softDeletes;

    protected $items = ['deleted_at'];
}
