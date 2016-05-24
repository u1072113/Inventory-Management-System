<?php

namespace App\Http\Controllers;

use Inventory\Repository\Customer\CustomerInterface;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use Response;


class CustomerController extends Controller
{
    public function __construct(CustomerInterface $customer)
    {
        $this->customer = $customer;
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
        $customers = $this->customer->getCustomers(compact('sort', 'search'));
        return view('customer/index')->with(compact('customers'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('customer/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $customer = $this->customer->createCustomer(Input::all());
        return redirect()->action('CustomerController@show', array('id' => $customer->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
//TODO Create Show after Creation of Invoices
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $customer = $this->customer->getCustomer($id);

        return view('customer/create')->with(compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->customer->updateCustomer($id, Input::all());
        return redirect()->action('CustomerController@show', array('customer' => $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->customer->deleteCustomer($id);
        return Response::json(['ok' => 'ok']);
    }

    /**
     * Gets Deleted Items
     */
    public function getDeleted()
    {
        $sort = Input::only('sortBy', 'direction');
        $search = Input::only('search');
        $deleted = array('deleted' => 1);
        $restore = 1;
        $customers = $this->customer->getCustomers(compact('sort', 'search', 'deleted'));
        return view('customer/index')->with(compact('customers', 'restore'));
    }

    /**
     * Restore Deleted Customer
     * @param $id
     */
    public function restore($id)
    {
        $this->customer->restoreDeletedCustomer($id);
        return redirect()->action('CustomerController@show', array('id' => $id));
    }
}
