<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Inventory\Presenters\PresentableTrait;
use Inventory\Traits\ActionTrait;
use Inventory\Traits\MultitenantTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use PresentableTrait;
    use MultitenantTrait;
    use ActionTrait;

    protected $presenter = 'Inventory\Presenters\ProductCategory';

    use SoftDeletes;

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
