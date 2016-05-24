<?php namespace Inventory\Presenters;

use Carbon;

/**
 * Class Product
 * @package Inventory\Presenters
 */
class PurchaseRequest extends Presenter
{
    protected $entity;

    public function requestDate()
    {
        return $this->entity->created_at->diffForHumans();
    }

}

