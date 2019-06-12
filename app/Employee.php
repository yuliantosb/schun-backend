<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Employee extends Model
{
    use SoftDeletes;
    protected $dates = ['date_of_birth'];
    protected $appends = ['photo_url', 'age'];

    public function user()
    {
        return $this->belongsTO('App\User');
    }

    public function getPhotoUrlAttribute()
    {
        return !empty($this->photo) ? url('storage/'.$this->photo)  : "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $this->user->email ) ) ) . "?d=mm&s=200";
    }

    public function getAgeAttribute()
    {
        return Carbon::parse($this->date_of_birth)->diff(Carbon::now())->format('%y y/o');
    }
}
