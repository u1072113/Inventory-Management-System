<?php

namespace App\Http\Controllers;

use App\PurchaseRequest;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Inventory\Repository\PurchaseRequest\PurchaseRequestInterface;
use Illuminate\Support\Facades\Input;
use Response;

class PurchaseRequestController extends Controller
{
    public function __construct(PurchaseRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sort = Input::only('sortBy', 'direction');
        $search = Input::only('search');
        $status = Input::query('status');
        $deleted = Input::query('deleted');
        $requests = $this->request->getPurchaseRequests(compact('sort', 'search', 'status', 'deleted'));
        return view('purchaserequest/index')->with(compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $products = $this->request->suggestPurchaseRequest();
        $requestItems = [];
        foreach ($products as $product) {
            $productArray = $product->toArray();
            array_push($requestItems, array("id" => $product->id, "values" => $productArray));
        }
        $product = json_encode($requestItems);
        return view('purchaserequest/newpurchaserequest')->with(compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->request->addPurchaseRequest(Input::all());
        return redirect()->action('PurchaseRequestController@index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $requestDetails = $this->request->getPurchaseRequest($id);
        $requestItems = array();
        foreach ($requestDetails->requests as $request) {
            array_push($requestItems, array(
                "id" => $request->id,
                "values" => array(
                    'id' => $request->prItemCode,
                    'prDescription' => $request->prDescription,
                    'reorderAmount' => '',
                    'prQty' => $request->prQty,
                    'prPurpose' => $request->prPurpose,

                )
            ));
        }
        $product = json_encode($requestItems);
        $requestDetails->notifyOnLpoCreation = explode(',', $requestDetails->notifyOnLpoCreation);
        // dd($requestDetails->notifyOnLpoCreation);
        return view('purchaserequest/newpurchaserequest')->with(compact('product', 'requestDetails'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->request->updatePurchaseOrder($id, Input::all());
        return redirect()->action('PurchaseRequestController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->request->deletePurchaseRequest($id);
        return Response::json(['ok' => 'ok']);
    }

    /**
     * Gets Deleted Purchase Requests
     * @return $this
     */
    public function getDeleted()
    {
        $sort = Input::only('sortBy', 'direction');
        $search = Input::only('search');
        $status = Input::query('status');
        $deleted = 1;
        $requests = $this->request->getPurchaseRequests(compact('sort', 'search', 'status', 'deleted'));
        $restore = 1;
        return view('purchaserequest/index')->with(compact('requests', 'restore'));
    }

    /**
     * Restores Deleted Purchase Request
     */
    public function restore()
    {
        $this->request->restore(Input::get('id'));
        return redirect()->action('PurchaseRequestController@index');
    }

    /**
     * Get Product with suggestion
     * @param $id
     */
    public function getProduct($id)
    {
        $product = $this->request->getProductForPurchaseRequest($id);
        $productArray = $product->toArray();
        $suggestion = array("id" => $product->id, "values" => $productArray);
        return $suggestion;
    }
}
