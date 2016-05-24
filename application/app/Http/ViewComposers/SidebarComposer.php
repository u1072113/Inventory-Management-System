<?php namespace App\Http\ViewComposers;

use Inventory\Printers\PrinterInterface;
use Inventory\Repository\Customer\CustomerInterface;
use Inventory\Repository\Department\DepartmentInterface;
use Inventory\Repository\Dispatch\DispatchInterface;
use Inventory\Repository\Invoice\InvoiceInterface;
use Inventory\Repository\Product\ProductInterface;
use Inventory\Repository\PurchaseOrder\PurchaseOrderInterface;
use Inventory\Repository\PurchaseRequest\PurchaseRequestInterface;
use Inventory\Repository\Restock\RestockInterface;
use Inventory\Repository\Staff\StaffInterface;
use Inventory\Repository\Supplier\SupplierInterface;
use Inventory\Repository\User\UserInterface;
use Illuminate\Contracts\View\View;

class SidebarComposer
{

    /**
     * @param PrinterInterface $printer
     * @param ProductInterface $product
     * @param UserInterface $user
     * @param DispatchInterface $dispatch
     * @param RestockInterface $restock
     * @param SupplierInterface $supplier
     * @param DepartmentInterface $department
     * @param PurchaseOrderInterface $purchaseOrder
     * @param StaffInterface $staff
     * @param CustomerInterface $customer
     * @param InvoiceInterface $invoice
     */
    public function __construct(
        PrinterInterface $printer,
        ProductInterface $product,
        UserInterface $user,
        DispatchInterface $dispatch,
        RestockInterface $restock,
        SupplierInterface $supplier,
        DepartmentInterface $department,
        PurchaseOrderInterface $purchaseOrder,
        StaffInterface $staff,
        CustomerInterface $customer,
        InvoiceInterface $invoice,
        PurchaseRequestInterface $request

    )
    {
        $this->department = $department;
        $this->product = $product;
        $this->user = $user;
        $this->dispatch = $dispatch;
        $this->restock = $restock;
        $this->supplier = $supplier;
        $this->printer = $printer;
        $this->purchaseOrder = $purchaseOrder;
        $this->staff = $staff;
        $this->invoice = $invoice;
        $this->customer = $customer;
        $this->request = $request;

    }

    public function compose(View $view)
    {
        #Dispatches
        $view->with('dispatchCount', $this->dispatch->getDispatchCount());


        #Restocks
        $view->with('restockCount', $this->restock->getRestocksCount());

        #Products
        $view->with('stockCount', $this->product->productsCount());

        #suppliers
        $view->with('supplierCount', $this->supplier->getSuppliersCount());

#departments
        $view->with('departmentCount', $this->department->getDepartmentCount());
        #users
        $view->with('userCount', $this->user->userCount());
        #Staff
        $view->with('staffCount', $this->staff->staffCount());
#printers
        $view->with('printerCount', $this->printer->getPrinterCount());

        #purchaseorder
        $view->with('purchaseOrder', $this->purchaseOrder->getPurchaseOrderCount());

        #User
        $view->with('users', $this->user->getCompanyMembers());

        #Invoice
        $view->with('invoiceCount', $this->invoice->count());

        #Customers
        $view->with('customerCount', $this->customer->getCustomerCount());

        #Purchase Request
        $view->with('requestCount', $this->request->getPurchaseRequestCount());


    }


}