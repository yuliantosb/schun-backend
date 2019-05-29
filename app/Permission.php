<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;
    
    public function parent()
    {
        return $this->belongsTo('App\Permission', 'parent_id', '_id');
    }

    public function children()
    {
        return $this->hasMany('App\Permission', 'parent_id', '_id');
    }
}
