<?php

namespace App\Permissions;

use App\Models\Admin\Role;
use App\Models\Admin\Permission;

trait HasPermissionsTrait
{
	public function hasPermissionTo($permission)
	{
		return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
	}

	public function hasRole(...$roles)
    {
    	foreach ($roles as $role) {
    		if ($this->roles->contains('name', $role)) {
    			return true;
    		}
    	}

    	return false;
    }

    protected function hasPermissionThroughRole($permission)
    {
    	foreach ($permission->roles as $role) {
    		if ($this->roles->contains($role)) {
    			return true;
    		}
    	}

    	return false;
    }

    protected function hasPermission($permission)
    {
    	return (bool) $this->permissions->where('name', $permission->name)->count();
    }

	public function roles()
    {
    	return $this->belongsToMany(Role::class, 'users_roles');
    }

    public function permissions()
    {
    	return $this->belongsToMany(Permission::class, 'users_permissions');
    }
}
