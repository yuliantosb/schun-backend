<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Role extends Model
{
    use softDeletes;
    protected $fillable = [
        'name', 'slug',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    protected $hidden = ['permissions'];

    public function users()
    {
        return $this->hasOne('App\User');
    }

    public function permissions()
    {
        return $this->embedsMany('App\Permission');
    }

    public function hasAccess(array $permissions) : bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission))
                return true;
        }
        return false;
    }

    private function hasPermission(string $permission) : bool
    {
        return $this->permissions->pluck($permission)->first() ?? false;
    }
}
