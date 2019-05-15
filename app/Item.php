<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Item extends Model
{
    //

    public function Uom() {
        return $this->belongsTo('App\Uom');
    }
}
