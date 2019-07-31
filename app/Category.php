<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = ['category_name'];

    public static function boot() {

        parent::boot();
        
        static::updating(function($table)  {
            $table->updated_by = auth()->user()->id;
        });

        static::saving(function($table)  {
            $table->created_by = auth()->user()->id;
        });
    }

    public function parent()
    {
        return $this->belongsTo('App\Category', 'parent_id', '_id');
    }

    public function children()
    {
        return $this->hasMany('App\Category', 'parent_id', '_id');
    }
}
