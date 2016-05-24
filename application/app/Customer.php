<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Inventory\Presenters\PresentableTrait;
use Inventory\Traits\ActionTrait;
use Inventory\Traits\MultitenantTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use PresentableTrait;
    use MultitenantTrait;
    use ActionTrait;
    use SoftDeletes;

    protected $presenter = 'Inventory\Presenters\Customer';
    protected $notNullable = ['addressName1', 'addressName2'];

    /** Turn Empties to Nullables */
    public static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            foreach ($model->attributes as $key => $value) {
                if (in_array($key, $model->notNullable) == false) {
                    $model->{$key} = empty($value) ? null : $value;
                }
            }
        });
    }

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
