<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Inventory\Traits\MultitenantTrait;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use SoftDeletes;
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function department()
    {
        return $this->belongsTo('App\Department', 'departmentId', 'id');
    }

    public function role()
    {
        return $this->hasOne('App\Role', 'id', 'role_id');
    }

    public function company()
    {
        return $this->hasOne('App\Company', 'id', 'companyId');
    }

    public function hasRole($roles)
    {
        $this->have_role = $this->getUserRole();
// Check if the user is a root account
        if ($this->have_role->name == 'Root') {
            return true;
        }
        if (is_array($roles)) {
            foreach ($roles as $need_role) {
                if ($this->checkIfUserHasRole($need_role)) {
                    return true;
                }
            }
        } else {
            return $this->checkIfUserHasRole($roles);
        }
        return false;
    }

    private function getUserRole()
    {
        return $this->role()->getResults();
    }

    private function checkIfUserHasRole($need_role)
    {
        return (strtolower($need_role) == strtolower($this->have_role->name)) ? true : false;
    }

    public function scopeName($query, $type)
    {
        if ($type != null or $type != "")
            return $query->whereName($type);
    }

    public function scopeEmail($query, $type)
    {
        if ($type != null or $type != "")
            return $query->whereEmail($type);
    }

    public function scopeRole_Id($query, $type)
    {
        if ($type != null or $type != "")
            return $query->whereRoleId($type);
    }

    public function scopeDepartmentId($query, $type)
    {
        if ($type != null or $type != "")
            return $query->whereDepartmentid($type);
    }
}
