<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Http\Requests;
use App\Patient;
use Carbon;
use Inventory\Repository\Department\DepartmentInterface;
use Inventory\Repository\Product\ProductInterface;
use Inventory\Repository\PurchaseOrder\PurchaseOrderInterface;
use Inventory\Repository\PurchaseRequest\PurchaseRequestInterface;
use Inventory\Repository\Restock\RestockInterface;
use Excel;
use Input;
use Redirect;
use Response;
use Validator;

class PurchaseOrderController extends Controller
{

    /**
     * @var ProductInterface
     */
    private $product;
    /**
     * @var PurchaseOrderInterface
     */
    private $purchaseOrder;
    /**
     * @var RestockInterface
     */
    private $restock;

    public function __construct(
        ProductInterface $product,
        PurchaseOrderInterface $purchaseOrder,
        RestockInterface $restock,
        DepartmentInterface $department,
        PurchaseRequestInterface $request
    )
    {

        $this->product = $product;
        $this->purchaseOrder = $purchaseOrder;
        $this->restock = $restock;
        $this->department = $department;
        $this->request = $request;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $sort = Input::only('sortBy', 'direction');
        $search = Input::only('search');
        $status = Input::query('status');
        $deleted = Input::query('deleted');
        $purchaseOrders = $this->purchaseOrder->getPurchaseOrders(compact('sort', 'search', 'status', 'deleted'));
        return view('purchaseorder.index')->with(compact('purchaseOrders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

        $departments = $this->department->departmentList();
        if (Input::has('request')) {
            $products = $this->purchaseOrder->getFromRequest(Input::get('request'));
            $request = $this->request->getPurchaseRequest(Input::get('request'));
            $requestNo = $request->requestNo;
        } else {
            $products = $this->purchaseOrder->suggestPurchaseOrder();
            $requestNo = null;
        }
        // dd($products);
        $orderItems = [];
        if (!is_array($products)) {
            foreach ($products as $product) {
                $productArray = $product->toArray();
                $productArray['taxable'] = "T";
                array_push($orderItems, array("id" => $product->id, "values" => $productArray));
            }
        } else {
            foreach ($products as $product) {
                $productArray = Helper::objectToArray($product);
                $productArray['taxable'] = "T";
                array_push($orderItems, array("id" => $productArray['id'], "values" => $productArray));
            }
        }

        $product = json_encode($orderItems);
        return view('purchaseorder.newpurchaseorder')->with(compact('product', 'departments', 'requestNo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {

        $validator = Validator::make(Input::all(), [
            'supplierName' => 'required',
            'termsOfPayment' => 'required',
            'lpoCurrencyType' => 'required',
            'departmentId' => 'required',
        ]);

        if ($validator->fails()) {
            $x = [];
            $orders = json_decode(Input::get('order'));
            //  dd($orders);
            foreach ($orders as $order) {
                array_push($x, array(
                    "id" => $order->id,
                    "values" => array(
                        'id' => $order->id,
                        'productName' => $order->productName,
                        'unitCost' => $order->unitCost,
                        'reorderAmount' => $order->reorderAmount,
                        'taxable' => $order->taxable,
                        'amount' => $order->amount,
                        'reorder' => $order->reorder,
                        'cost' => $order->cost,

                    )
                ));
            }

            Input::replace(array('order' => $x));

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $this->purchaseOrder->addPurchaseOrder(Input::all());
        return Redirect::action('PurchaseOrderController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $departments = $this->department->departmentList();
        $product = $this->purchaseOrder->getPurchaseOrder($id);
        $id = $product->id;
        $x = [];
        foreach ($product->orders as $order) {
            array_push($x, array(
                "id" => $order->id,
                "values" => array(
                    'id' => $order->poItemCode,
                    'productName' => $order->poDescription,
                    'unitCost' => $order->poUnitPrice,
                    'reorder' => $order->poQty,
                    'taxable' => $order->taxable,

                )
            ));
        }
        $orders = json_encode($x);
        $product->deliverBy = Carbon::parse($product->polDeliverBy)->format('Y/m/d');
        $product->termsOfPayment = $product->polTermsOfPayment;
        $product->supplierName = $product->polSupplierId;
        return view('purchaseorder.editpurchaseorder')->with(compact('product', 'orders', 'id', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $this->purchaseOrder->updatePurchaseOrder($id, Input::all());
        return Redirect::action('PurchaseOrderController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->purchaseOrder->deletePurchaseOrder($id);
        return Response::json(['ok' => 'ok']);
    }

    public function getDeleted()
    {
        $sort = Input::only('sortBy', 'direction');
        $search = Input::only('search');
        $status = Input::query('status');
        $deleted = 1;
        $purchaseOrders = $this->purchaseOrder->getPurchaseOrders(compact('sort', 'search', 'status', 'deleted'));
        $restore = 1;
        return view('purchaseorder.index')->with(compact('purchaseOrders', 'restore'));
    }

    public function restore()
    {
        $this->purchaseOrder->restore(Input::get('id'));
        return redirect()->action('PurchaseOrderController@index');
    }

    /**
     * Get Restock with suggestion
     * @param $id
     */
    public function getRestock($id)
    {
        $product = $this->purchaseOrder->getProductWithRestockSuggestion($id);
        $productArray = $product->toArray();
        $productArray['taxable'] = "T";
        $suggestion = array("id" => $product->id, "values" => $productArray);
        return $suggestion;
    }

    /**
     * Used to get Restock View for purchase order
     * @param $id
     * @return $this
     */
    public function getRestockFromPurchaseOrder($id)
    {
        $product = $this->purchaseOrder->getPurchaseOrder($id);
        $id = $product->id;
        $x = [];
        foreach ($product->orders as $order) {
            //Ignore uploaded data

            if ($order->poQty != $order->delivered) {
                array_push($x, array(
                    "id" => $order->id,
                    "values" => array(
                        'orderId' => $order->id,
                        'id' => $order->poItemCode,
                        'productName' => $order->poDescription,
                        'unitCost' => $order->poUnitPrice,
                        'reorder' => $order->poQty,
                        'fullDelivery' => $order->fullDelivery,
                        'delivered' => $order->delivered,
                        "received" => $order->poQty - $order->delivered
                    )
                ));
            }
        }
        $orders = json_encode($x);
        $product->deliverBy = Carbon::parse($product->polDeliverBy)->format('Y/m/d');
        $product->termsOfPayment = $product->polTermsOfPayment;
        $product->supplierName = $product->polSupplierId;
        return view('purchaseorder.restockfrompurchaseorder')->with(compact('product', 'orders', 'id'));
    }

    /**
     * Performs a restock from purchase order
     * @return mixed
     */
    public function postRestockFromPurchaseOrder()
    {
        $orders = json_decode(Input::get('order'));

        foreach ($orders as $delivered) {
            $delivered->supplierId = Input::get('supplierId');
            $delivered->polId = Input::get('polId');
            if ($delivered->received > 0) {
                if ($delivered->received < $delivered->reorder) {
                    $delivered->fullDelivery = 0;
                    $delivered->partDelivery = 1;
                    $this->purchaseOrder->restockFromPurchaseOrder($delivered);
                } elseif ($delivered->received == $delivered->reorder) {
                    $delivered->fullDelivery = 1;
                    $delivered->partDelivery = 0;
                    $this->purchaseOrder->restockFromPurchaseOrder($delivered);
                }
            }
        }
        $this->purchaseOrder->updateDelivery(Input::get('polId'));
        return Redirect::action('PurchaseOrderController@index');
    }
}
