<?php namespace Inventory\Repository\UserRoles;

use App\Role;

/**
 * Class UserRolesEntity
 * @package Inventory\Repository\UserRoles
 */
class UserRolesEntity implements UserRoleInterface
{

    /**
     * Returns all user roles
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return Role::all();
    }

    /**
     * @return mixed
     */
    public function roleList()
    {
        return Role::lists('name', 'id');
    }

    /**
     * @return mixed
     */
    public function rolesCount()
    {
        return Role::all()->count();
    }
}
