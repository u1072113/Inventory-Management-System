<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Inventory\Presenters\PresentableTrait;
use Inventory\Traits\MultitenantTrait;
use Inventory\Traits\ActionTrait;
class Dispatch extends Model
{
    use ActionTrait;
    use PresentableTrait;
    use MultitenantTrait;
    protected $presenter = 'Inventory\Presenters\Dispatch';
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    //
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dispatches';

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

    public function getDates()
    {
        return ['deleted_at', 'created_at', 'updated_at'];
    }
    public function product()
    {
        return $this->belongsTo('App\Product', 'dispatchedItem', 'id');
    }

    public function staff()
    {
        return $this->belongsTo('App\Staff', 'dispatchedTo', 'id');
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'createdBy', 'id');
    }

    public function updater()
    {
        return $this->belongsTo('App\User', 'updatedBy', 'id');
    }
    public function scopeDispatchedItem($query, $type)
    {
        if ($type != null or $type != "")
            return $query->whereDispatcheditem($type);
    }

    public function scopeDispatchedTo($query, $type)
    {
        if ($type != null or $type != "")
            return $query->whereDispatchedto($type);
    }
}
