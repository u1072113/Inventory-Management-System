<?php


namespace Inventory\Traits;


trait MultitenantTrait
{
    public static function bootMultitenantTrait()
    {
        static::addGlobalScope(new MultitenantScope());
    }

    public static function allTenants()
    {
        return (new static())->newQueryWithoutScope(new MultitenantScope());
    }
}