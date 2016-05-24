<?php namespace App\Http\Controllers;

use App\Http\Requests;
use Inventory\Repository\Department\DepartmentInterface;
use Inventory\Repository\Dispatch\DispatchInterface;
use Inventory\Repository\Product\ProductInterface;
use Inventory\Repository\Restock\RestockInterface;
use Inventory\Repository\Supplier\SupplierInterface;
use Inventory\Repository\User\UserInterface;
use Illuminate\Http\Request;
use Inventory\Printers\PrinterInterface;
class DashboardController extends Controller
{

    /**
     * @var PrinterInterface
     */
    private $printer;

    public function __construct(
        ProductInterface $product,
        DispatchInterface $dispatch,
        UserInterface $user,
        RestockInterface $restock,
        DepartmentInterface $department,
        SupplierInterface $supplier,
        PrinterInterface $printer
)
    {
        $this->product = $product;
        $this->dispatch = $dispatch;
        $this->user = $user;
        $this->restock = $restock;
        $this->department = $department;
        $this->supplier = $supplier;
        $this->printer = $printer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $displayMessage= $request->session()->pull('displayMessage', 'no');
        $productCount = $this->product->productsCount();
        $departmentCount = $this->department->getDepartmentCount();
        $userCount = $this->user->userCount();
        $supplierCount = $this->supplier->getSuppliersCount();
        $supplierAmountReport = $this->supplier->suppliersReportAmount();
        $data = json_encode($supplierAmountReport);
        $supplierAmountReport = str_replace("'", "", $data);
        $dispatchTrend = $this->dispatch->getDailyDispatchReport();
        $totalRestockCost = $this->restock->getCost(0);
        $totalDispatchCost = $this->dispatch->getCost(0);
        $lowStock = $this->product->getLowStockCount();
        if($printedPages){
            $printedPages = $printedPages->pages;
        }
        return View('dashboard/index')->with(compact('productCount', 'departmentCount', 'userCount', 'supplierCount', 'supplierAmountReport', 'dispatchTrend', 'totalRestockCost', 'totalDispatchCost', 'lowStock','displayMessage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}
