<?php namespace App\Http\ViewComposers;


use Inventory\Repository\Department\DepartmentInterface;
use Inventory\Repository\Staff\StaffInterface;
use Illuminate\Contracts\View\View;
use Inventory\Repository\Product\ProductInterface;

class DispatchesComposer
{

    public function __construct(ProductInterface $product, StaffInterface $staff, DepartmentInterface $department)
    {
        $this->product = $product;
        $this->staff = $staff;
        $this->department = $department;
    }

    public function compose(View $view)
    {
        $view->with('products', $this->product->productsList());
        $view->with('users', $this->staff->staffList());
        $view->with('departments', $this->department->departmentList());
    }


}
