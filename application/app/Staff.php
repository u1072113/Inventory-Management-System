<?php

namespace App;

use Inventory\Presenters\PresentableTrait;
use Inventory\Traits\ActionTrait;
use Inventory\Traits\MultitenantTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use PresentableTrait;
    use MultitenantTrait;
    use SoftDeletes;
    use ActionTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function creator()
    {
        return $this->belongsTo('App\User', 'createdBy', 'id');
    }

    public function updater()
    {
        return $this->belongsTo('App\User', 'updatedBy', 'id');
    }

    public function department()
    {
        return $this->belongsTo('App\Department', 'departmentId', 'id');
    }
}
