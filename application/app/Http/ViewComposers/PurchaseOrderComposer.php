<?php namespace App\Http\ViewComposers;

use App\Country;
use Inventory\Repository\Department\DepartmentInterface;
use Inventory\Repository\Dispatch\DispatchInterface;
use Inventory\Repository\Product\ProductInterface;
use Inventory\Repository\PurchaseOrder\PurchaseOrderInterface;
use Inventory\Repository\Supplier\SupplierInterface;
use Inventory\Repository\User\UserInterface;
use Illuminate\Contracts\View\View;
use App\PurchaseOrderList;
use Auth;
use App\Company;

class PurchaseOrderComposer
{

    /**
     * @var SupplierInterface
     */
    private $supplier;

    public function __construct(DepartmentInterface $department, ProductInterface $product, SupplierInterface $supplier, PurchaseOrderInterface $purchaseOrder)
    {
        $this->product = $product;

        $this->supplier = $supplier;

        $this->purchaseOrder = $purchaseOrder;

        $this->department = $department;
    }

    public function compose(View $view)
    {
        $view->with('products', $this->purchaseOrder->autoSuggestList());
        $view->with('suppliers', $this->supplier->supplierList());
        $view->with('undeliveredCount', PurchaseOrderList::undelivered()->count());
        $view->with('deliveredCount', PurchaseOrderList::delivered()->count());
        $view->with('partdeliveredCount', PurchaseOrderList::partdelivery()->count());
        $view->with('waitingApprovalCount', PurchaseOrderList::waitingApproval()->count());
        $view->with('lateDeliveryCount', PurchaseOrderList::lateDelivery()->count());
        $view->with('currency', Country::select('currency')->lists('currency', 'currency'));
        $view->with('departments', $this->department->departmentList());
        $view->with('defaultLpoTaxAmount', Company::find(Auth::user()->companyId)->defaultLpoTaxAmmount);
    }


}