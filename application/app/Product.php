<?php namespace App;

use Inventory\Presenters\PresentableTrait;
use Inventory\Traits\ActionTrait;
use Inventory\Traits\MultitenantTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use PresentableTrait;
    use MultitenantTrait;
    use ActionTrait;
    protected $presenter = 'Inventory\Presenters\Product';

    use SoftDeletes;

    protected $revisionFormattedFieldNames = array(
        'amount' => 'Product Ammount',
        'small_name' => 'Nickname',
        'deleted_at' => 'Deleted At'
    );
    protected $dates = ['deleted_at'];
    //
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

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

    public function creator()
    {
        return $this->belongsTo('App\User', 'createdBy', 'id');
    }

    public function updater()
    {
        return $this->belongsTo('App\User', 'updatedBy', 'id');
    }

    /**
     * Get the comments for the blog post.
     */
    public function restocks()
    {
        return $this->hasMany('App\Restock', 'productID', 'id');
    }

    public function setProductSerialAttribute($value)
    {
        $this->attributes['productSerial'] = trim($value) !== '' ? $value : null;
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = trim($value) !== '' ? $value : null;
    }


    public function setMaximumOrderAmountAttribute($value)
    {
        $this->attributes['maximumOrderAmount'] = trim($value) !== '' ? $value : null;
    }

    public function setExpirationDateAttribute($value)
    {
        $this->attributes['expirationDate'] = trim($value) !== '' ? $value : null;
    }

    public function setBarcodeAttribute($value)
    {
        $this->attributes['barcode'] = trim($value) !== '' ? $value : null;
    }

    public function setQrcodeAttribute($value)
    {
        $this->attributes['qrcode'] = trim($value) !== '' ? $value : null;
    }

    public function setProductImageAttribute($value)
    {
        $this->attributes['productImage'] = trim($value) !== '' ? $value : null;
    }

    public function setReorderAmountAttribute($value)
    {
        $this->attributes['reorderAmount'] = trim($value) !== '' ? $value : null;
    }

    public function setUnitCostAttribute($value)
    {
        $this->attributes['unitCost'] = trim($value) !== '' ? $value : null;
    }

    public function getProductSerialAttribute($value)
    {
        return trim($value) !== '' ? $value : null;
    }

    public function getAmountAttribute($value)
    {
        return trim($value) !== '' ? $value : null;
    }


    public function getMaximumOrderAmountAttribute($value)
    {
        return trim($value) !== '' ? $value : null;
    }

    public function getExpirationDateAttribute($value)
    {
        return trim($value) !== '' ? $value : null;
    }

    public function getBarcodeAttribute($value)
    {
        return trim($value) !== '' ? $value : null;
    }

    public function getQrcodeAttribute($value)
    {
        return trim($value) !== '' ? $value : null;
    }

    public function getProductImageAttribute($value)
    {
        return trim($value) !== '' ? $value : null;
    }

    public function scopeProductName($query, $type)
    {
        if ($type != null or $type != "") {
            return $query->where('productName', 'like', '%' . $type . '%');
        }
    }

    public function scopeAmount($query, $type)
    {
        if ($type != null or $type != "") {
            return $query->whereAmount($type);
        }
    }

    public function scopeReorderAmount($query, $type)
    {
        if ($type != null or $type != "") {
            return $query->whereReorderamount($type);
        }
    }

    public function scopeProductSerial($query, $type)
    {
        if ($type != null or $type != "") {
            return $query->whereProductserial($type);
        }
    }

    public function scopeLocation($query, $type)
    {
        if ($type != null or $type != "") {
            return $query->whereLocation($type);
        }
    }
}
