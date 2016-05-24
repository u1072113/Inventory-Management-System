<?php namespace App\Http\Controllers;

use App\Http\Requests;
use Carbon;
use Inventory\Repository\Supplier\SupplierInterface;
use Excel;
use Flash;
use Illuminate\Http\Request;
use Input;
use Mail;
use Redirect;
use Response;

class SupplierController extends Controller
{

    public function __construct(SupplierInterface $supplier)
    {
        $this->supplier = $supplier;

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        Flash::message('List of your suppliers and their total amount you have paid them (Restock Cost per supplier)',
            'info');
        $page = Input::get('page', 1);
        //$path = Input::path(); Input::query
        $sort = Input::only('sortBy', 'direction');
        $search = Input::only('search');
        $suppliers = $this->supplier->all(compact('sort', 'page', 'search'));
        $supplierAmountReport = $this->supplier->suppliersReportAmount();
        $message = "List of all suppliers and what you have spent on them";
        $data = json_encode($supplierAmountReport);
        $data = str_replace("'", "", $data);
        return View('suppliers/index')->with(compact('suppliers', 'message'))->with('supplierAmountReport', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        return View('suppliers/newsupplier');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
        $this->supplier->createSupplier(Input::all());
        return Redirect::action('SupplierController@index');
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
        $page = Input::get('page', 1);
        //$path = Input::path(); Input::query
        $sort = Input::only('sortBy', 'direction');
        $search = Input::only('search');
        $suppliers = $this->supplier->all(compact('sort', 'page', 'search'));
        $supplier = $this->supplier->getSupplierById($id);
        return View('suppliers/newsupplier')->with(compact('suppliers'))->with(compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $this->supplier->updateSupplier($id, Input::all());
        return Redirect::action('SupplierController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->supplier->deleteSupplier($id);
        return Response::json(['ok' => 'ok']);
    }

    public function getDeleted()
    {
        $suppliers = $this->supplier->getDeletedSuppliers();
        $restore = 1;
        $message = "All Deleted Suppliers";
        return View('suppliers/index')->with(compact('suppliers', 'restore', 'message'));
    }

    public function restore($id)
    {
        $this->supplier->restoreSupplier($id);
        return Redirect::action('SupplierController@index');
    }

    public function export()
    {
        //dd($this->supplier->allSuppliersReport());
        $format = Input::query('type');
        $file = Excel::create('Filename', function ($excel) {

            // Set the title
            $excel->setTitle('Suppliers and total amount paid to them');

            // Chain the setters
            $excel->setCreator('Stock Control System')
                ->setCompany(env('COMPANY_NAME'));

            // Call them separately
            $excel->setDescription('Suppliers and Total Amount');
            $excel->sheet('All Suppliers', function ($sheet) {
                $sheet->freezeFirstRowAndColumn();
                $sheet->fromModel($this->supplier->allSuppliersReport());
            });

            $excel->sheet('All Deleted Suppliers Suppliers', function ($sheet) {
                $sheet->freezeFirstRowAndColumn();
                $sheet->fromModel($this->supplier->allDeletedSuppliersReport());
            });
        });

        if (Input::has('email')) {
            $email = Input::get('email');
            $save_details = $file->store('xlsx');
            $content = "Please find attached a list of suppliers report from the stock control system";
            Mail::send('emails.product', array('content' => $content), function ($message) use ($save_details, $email) {
                $message->to($email)->subject('List of Suppliers Report');
                $message->attach($save_details['full']);
            });
            return Redirect::action('SupplierController@index');
        } else {
            $file->download($format);
        }
    }
}
