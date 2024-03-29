<?php namespace App;

use Inventory\Presenters\PresentableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Inventory\Traits\MultitenantTrait;
use Inventory\Traits\ActionTrait;
class Supplier extends Model
{
    use PresentableTrait;
    use MultitenantTrait;
    use ActionTrait;
    protected $presenter = 'Inventory\Presenters\Supplier';
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    //
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'suppliers';

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
    protected $hidden = [];

    public function setSupplierDiscountAttribute($value)
    {
        $this->attributes['supplierDiscount'] = trim($value) !== '' ? $value : null;
    }

    public function scopeSupplierName($query, $type)
    {
        if ($type != null or $type != "") {
            return $query->where('supplierName', 'like', '%' . $type . '%');
        }
    }

    public function scopeEmail($query, $type)
    {
        if ($type != null or $type != "") {
            return $query->whereEmail($type);
        }
    }

    public function scopeIsBlocked($query, $type)
    {
        if ($type != null or $type != "") {
            return $query->whereIsblocked($type);
        }
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'createdBy', 'id');
    }

    public function updater()
    {
        return $this->belongsTo('App\User', 'updatedBy', 'id');
    }

}


