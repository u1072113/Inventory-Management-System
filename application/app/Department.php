<?php namespace App;

use Inventory\Presenters\PresentableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Inventory\Traits\MultitenantTrait;
use Inventory\Traits\ActionTrait;
class Department extends Model
{
    use ActionTrait;
    use PresentableTrait;
    use MultitenantTrait;
    protected $presenter = 'Inventory\Presenters\Department';
    use softDeletes;

    //
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'departments';

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
        return ['deleted_at', 'created_at', 'updated_at', 'budgetStartDate', 'budgetEndDate'];
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'createdBy', 'id');
    }

    public function updater()
    {
        return $this->belongsTo('App\User', 'updatedBy', 'id');
    }
    public function setBudgetLimitAttribute($value)
    {
        $this->attributes['budgetLimit'] = trim($value) !== '' ? $value : null;
    }

    public function setDepartmentEmailAttribute($value)
    {
        $this->attributes['departmentEmail'] = trim($value) !== '' ? $value : null;
    }

    public function setBudgetStartDateAttribute($value)
    {
        $this->attributes['budgetStartDate'] = trim($value) !== '' ? $value : null;
    }

    public function setBudgetEndDateAttribute($value)
    {
        $this->attributes['budgetEndDate'] = trim($value) !== '' ? $value : null;
    }

}
