<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    public function parent()
    {
        return $this->belongsTo('App\Category', 'parent_id', '_id');
    }

    public function children()
    {
        return $this->hasMany('App\Category', 'parent_id', '_id');
    }
}
