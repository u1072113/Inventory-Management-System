<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Inventory\Presenters\PresentableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Inventory\Traits\MultitenantTrait;
use Inventory\Traits\ActionTrait;
use Carbon\Carbon;

class PurchaseOrderList extends Model
{
    use SoftDeletes;
    use PresentableTrait;
    use MultitenantTrait;
    use ActionTrait;
    protected $presenter = 'Inventory\Presenters\PurchaseOrder';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_orders_list';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function orders()
    {
        return $this->hasMany('App\PurchaseOrder', 'plId', 'id');
    }

    public function supplier()
    {
        return $this->hasOne('App\Supplier', 'id', 'polSupplierId')->withTrashed();
    }

    public function department()
    {
        return $this->hasOne('App\Department', 'id', 'departmentId');
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'createdBy', 'id');
    }

    public function updater()
    {
        return $this->belongsTo('App\User', 'updatedBy', 'id');
    }

    /**
     * Get Delivered Items
     * @param $query
     * @param $type
     * @return mixed
     */
    public function scopeDelivered($query)
    {
        return $query->whereFulldelivery(1);

    }

    /**
     * Get Undelivered Items
     * @param $query
     * @return mixed
     */
    public function scopeUndelivered($query)
    {
        return $query->whereFulldelivery(0)->wherePartdelivery(0)->whereLpostatus('approved');
    }

    /**
     * Get Undelivered Items
     * @param $query
     * @return mixed
     */
    public function scopeLateDelivery($query)
    {
        return $query->whereFulldelivery(0)->where('polDeliverBy', '<', Carbon::today());
    }

    public function scopeWaitingApproval($query)
    {
        return $query->whereLpostatus('Awaiting Approval');
    }

    /**
     * Get Part Delivery
     * @param $query
     * @return mixed
     */
    public function scopePartdelivery($query)
    {
        return $query->wherePartdelivery(1);
    }

}
