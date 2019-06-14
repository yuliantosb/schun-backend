<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Setting extends Model
{
    public function scopeGetSetting($query)
    {
        $data = $query->first();
        return $data;
    }
}
