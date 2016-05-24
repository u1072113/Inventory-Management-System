<?php  namespace App\Http\ViewComposers;

//App::singleton('ProductsComposer');
use Inventory\Repository\Product\ProductInterface;
use Illuminate\Contracts\View\View;
use Input;

class ProductsComposer
{

    public function __construct(ProductInterface $product)
    {
        $this->product = $product;
    }

    public function compose(View $view)
    {
        $view->with('stockCount', $this->product->productsCount());
        $view->with('sort', Input::only('sortBy', 'direction'));
    }


}