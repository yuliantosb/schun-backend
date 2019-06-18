<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Store extends Model
{
     use SoftDeletes;

     public static function boot() {

          parent::boot();
          
          static::updating(function($table)  {
              $table->updated_by = auth()->user()->id;
          });
  
          static::saving(function($table)  {
              $table->created_by = auth()->user()->id;
          });
     }
}
