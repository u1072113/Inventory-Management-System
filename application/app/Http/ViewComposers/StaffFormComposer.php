<?php namespace App\Http\ViewComposers;

use Inventory\Repository\Department\DepartmentInterface;
use Inventory\Repository\UserRoles\UserRoleInterface;
use Inventory\Repository\User\UserInterface;
use Illuminate\Contracts\View\View;

class StaffFormComposer
{

    public function __construct(DepartmentInterface $department, UserRoleInterface $roles, UserInterface $user)
    {
        $this->department = $department;
        $this->roles = $roles;
        $this->user = $user;
    }

    public function compose(View $view)
    {
        $view->with('departments', $this->department->departmentList());
        $view->with('roles', $this->roles->roleList());
    }


}