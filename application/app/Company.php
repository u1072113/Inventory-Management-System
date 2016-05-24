<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Inventory\Presenters\PresentableTrait;
class Company extends Model
{
    protected $presenter = 'Inventory\Presenters\Company';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    use SoftDeletes;
}
