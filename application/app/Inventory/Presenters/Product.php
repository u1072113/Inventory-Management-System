<?php namespace Inventory\Presenters;

/**
 * Class Product
 * @package Inventory\Presenters
 */
class Product extends Presenter
{
    protected $entity;


    /**
     * @return string
     */
    public function productName()
    {
        return strtoupper($this->entity->productName);
    }

    /**
     * @return string
     */
    public function unitCost()
    {
        return number_format($this->entity->unitCost, 2, ".", ",");
    }

    /**
     * @return string
     */
    public function amount()
    {
        return number_format($this->entity->amount, 2, ".", ",");
    }

    /**
     * @return string
     */
    public function reorderAmount()
    {
        return number_format($this->entity->reorderAmount, 0, ".", ",");
    }

    /**
     * @return string
     */
    public function percentage()
    {
        if ($this->entity->reorderAmount > 0) {
            $val = ($this->entity->amount / $this->entity->reorderAmount) * 100;
        } else {
            return "100%";
        }
        if ($val > 100) {
            return "100%";
        } else {
            return $val . "%";
        }
    }

    /**
     * @return string
     */
    public function viewPercentage()
    {
        if ($this->entity->amount < $this->entity->reorderAmount) {
            return "progress-bar-danger";
        }
        return "progress-bar-success";
    }

    public function editDetails()
    {
        return 'Created By : ' . $this->entity->creator->name . '<br/> Updated By : ' . $this->entity->creator->name;
    }
}
