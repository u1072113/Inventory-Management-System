<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Inventory\Presenters\PresentableTrait;
use Inventory\Traits\MultitenantTrait;
use Inventory\Traits\ActionTrait;

class PurchaseRequestItem extends Model
{
    use PresentableTrait;
    use MultitenantTrait;
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
}
