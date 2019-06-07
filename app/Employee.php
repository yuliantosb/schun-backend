<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;
    protected $dates = ['date_of_birth', 'hire_date'];
}
