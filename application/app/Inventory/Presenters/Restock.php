<?php namespace Inventory\Presenters;

class Restock extends Presenter
{
    protected $entity;

    /**
     * @return string
     */
    public function productName()
    {
        return strtoupper($this->entity->product->productName);
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
    public function itemCost()
    {
        return number_format($this->entity->itemCost, 2, ".", ",");
    }

    /**
     * @return string
     */
    public function supplierName()
    {
        if ($this->entity->supplier) {
            return $this->entity->supplier->supplierName;
        } else {
            return "- Deleted Supplier -";
        }
    }

    /**
     * @return null|string
     */
    public function hasDownload()
    {
        if ($this->entity->restockDocs != "") {
            return " <a href='" . action('RestockController@getDownload', array('file'=>$this->entity->restockDocs)) . "'><i class='fa fa-download'></i></a>";
        }
        return null;
    }
}
