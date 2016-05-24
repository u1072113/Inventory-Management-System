<?php


namespace Inventory\Traits;

use Auth;

trait ActionTrait
{

    public static function bootActionTrait()
    {
        static::creating(function ($entry) {
            if (Auth::check()) {
                $entry->companyId = Auth::user()->companyId;
                $entry->createdBy = Auth::user()->id;
            }
        });

        static::updating(function ($entry) {
            if (Auth::check()) {
                $entry->companyId = Auth::user()->companyId;
                $entry->updatedBy = Auth::user()->id;
            }

        });

        /**
        static::updating(function ($model) {
       //     dd($model->revisionFormattedFieldNames);
            foreach ($model->getDirty() as $attribute => $value) {
                dd($model->revisionFormattedFieldNames[$attribute]);
                $original = $model->getOriginal($attribute);
                echo "Changed $attribute from '$original' to '$value'<br/>\r\n";
            }
            dd("here");
        });
**/


    }
}