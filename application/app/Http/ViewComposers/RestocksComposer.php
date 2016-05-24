<?php namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Inventory\Repository\User\UserInterface;
use Inventory\Repository\Restock\RestockInterface;
use Inventory\Repository\Product\ProductInterface;
use Inventory\Repository\Supplier\SupplierInterface;
class RestocksComposer {

    public function __construct(RestockInterface $restock,ProductInterface $product,SupplierInterface $supplier){
        $this->restock = $restock;
        $this->product = $product;
        $this->supplier = $supplier;
    }

    public function compose(View $view)
    {
        $view->with('products', $this->product->productsList());
        $view->with('allSuppliers', $this->supplier->supplierList());
    }


}