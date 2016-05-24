<?php namespace Inventory\Presenters;

use Carbon;

/**
 * Class Product
 * @package Inventory\Presenters
 */
class PurchaseOrder extends Presenter
{
    protected $entity;

    /**
     * Returns count of delivered items
     * @return string
     */
    public function delivered()
    {
        $string = $this->entity->orders->sum('delivered') . '/' . $this->entity->orders->sum('poQty');

        if ($this->entity->fullDelivery == 1) {
            $string = $string . ' ( in ' . Carbon::createFromFormat('Y-m-d', $this->entity->lpoDate)->diffInDays($this->entity->updated_at) . ' days)';
        }

        return $string;
    }

    /**
     * Returns count of delivered items
     * @return string
     */
    public function fullDelivery()
    {
        if ($this->entity->orders->sum('delivered') == $this->entity->orders->sum('poQty')) {
            return true;
        } else {
            return false;
        }


    }

    public function delivery()
    {
        $deliveryDate = Carbon::parse($this->entity->polDeliverBy);
        $dateCreated = $this->entity->created_at;
        $days = $dateCreated->diffInDays($deliveryDate);
        return Carbon::parse($this->entity->polDeliverBy)->format('d-M-y');
    }

    public function deliveryPopOver()
    {
        $deliveryDate = Carbon::parse($this->entity->polDeliverBy);
        $dateCreated = $this->entity->created_at;
        $days = $dateCreated->diffInDays($deliveryDate);
        return "Delivery should be made in  " . $days . " days";
    }

    public function created()
    {
        $days = $this->entity->created_at->diffForHumans();
        return $this->entity->created_at->format('d-M-y');
    }

    public function createdPopOver()
    {
        $days = $this->entity->created_at->diffForHumans();
        return "LPO was created   " . $days;
    }

    public function supplierContact()
    {
        return $this->entity->supplier->email;
    }

    public function supplierDetails()
    {
        return $this->entity->supplier->supplierName . ' Tel - ' . $this->entity->supplier->phone;
    }

    public function favourite()
    {
        if ($this->entity->isFavourite) {
            return "<a href='" . action('PurchaseOrderController@create',
                array('id' => $this->entity->id)) . "'><i class='fa fa-star'></i></a>";
        }
    }

    public function totalCash()
    {
        $total = 0;
        foreach ($this->entity->orders as $order) {
            $amount = $order->poQty * $order->poUnitPrice;
            $total = $total + $amount;
        }
        if ($this->entity->lpoCurrencyType == "KES") {
            return "KES " . number_format($total, 2);
        } elseif ($this->entity->lpoCurrencyType == "USD") {
            return "<i class='fa fa-usd'></i> " . number_format($total, 2);
        } elseif ($this->entity->lpoCurrencyType == "EURO") {
            return "<i class='fa fa-euro'></i> " . number_format($total, 2);
        }
    }

}

