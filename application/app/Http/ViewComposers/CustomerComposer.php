<?php namespace App\Http\ViewComposers;

use App\User;
use Inventory\Repository\Staff\StaffInterface;
use Illuminate\Contracts\View\View;
use Inventory\Repository\Product\ProductInterface;
use Inventory\Repository\User\UserInterface;
use Inventory\Repository\Dispatch\DispatchInterface;
use App\Country;

class CustomerComposer
{

    public function __construct()
    {

    }

    public function compose(View $view)
    {
        $view->with('countries', Country::select('country')->lists('country', 'country'));

    }


}