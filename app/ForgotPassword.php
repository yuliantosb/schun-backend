<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class ForgotPassword extends Model
{
    use SoftDeletes;
    protected $fillable = ['email'];
}
