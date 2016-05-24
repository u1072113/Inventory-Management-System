<?php namespace Inventory\Presenters;

class Dispatch extends Presenter
{
    protected $entity;

    /**
     * @return string
     */
    public function totalCost()
    {
        return number_format($this->entity->totalCost, 2, ".", ",");
    }

    /**
     * @return string
     */
    public function productName()
    {
        return strtoupper($this->entity->product->productName);
    }

    /**
     * @return mixed
     */
    public function createdAt()
    {
        return $this->entity->updated_at;
    }
}
