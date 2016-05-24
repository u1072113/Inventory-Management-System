<?php namespace App\Http\Controllers;

use App\Helper;
use App\Http\Requests;
use App\Http\Requests\RestockFormRequest;
use Auth;
use Carbon;
use Inventory\Repository\Product\ProductInterface;
use Inventory\Repository\Restock\RestockInterface;
use Inventory\Repository\Supplier\SupplierInterface;
use Excel;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Image;
use Input;
use Mail;
use Redirect;
use Response;

class RestockController extends Controller
{

    public function __construct(
        RestockInterface $restock,
        ProductInterface $product,
        SupplierInterface $supllier
    )
    {
        $this->product = $product;
        $this->restock = $restock;
        $this->supplier = $supllier;
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
        $allRestocks = $this->restock->all(compact('sort', 'search'));
        $message = "Items that have been restocked recent first";
        return View('restocks/index')->with(compact('allRestocks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View('restocks/restock');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(RestockFormRequest $restock)
    {
        //
        $user = Auth::user();
        $data = Input::all();
        $data['receivedBy'] = $user->id;
        if ($data['unitCost'] == " " && is_numeric($data['itemCost']) && is_numeric($data['amount'])) {
            if ($data['itemCost'] > 0 && $data['amount'] > 0) {
                $data['unitCost'] = $data['itemCost'] / $data['amount'];
            }
        }
        $this->restock->restock($data);

        Flash::message('Restock was successful', 'info');
        return Redirect::action('RestockController@index');
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
        $restock = $this->restock->getById($id);
        return View('restocks/restock')->with(compact('restock'));
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
        $this->restock->updateRestock($id, Input::all());
        return Redirect::action('RestockController@index');
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
        $this->restock->delete($id, Input::all());
        return Response::json(['ok' => 'ok']);
    }

    public function getDeleted()
    {
        Flash::message('Deleted Restocks that you can restore', 'info');
        $allRestocks = $this->restock->getDeleted();
        $restore = 1;
        $message = "Items that are below Stock Warning Levels";
        return View('restocks/index')->with(compact('allRestocks', 'restore', 'message'));
    }

    public function getDefective()
    {
        $allRestocks = $this->restock->getDefective();
        $restore = 1;
        $message = "Items that have been deleted and marked as defective";
        return View('restocks/index')->with(compact('allRestocks', 'restore', 'message'));
    }

    public function restore($id)
    {
        $this->restock->restoreRestock($id);
        return Redirect::action('RestockController@index');
    }

    public function export()
    {
        $filename = Carbon::now()->format('Ymd_') . "RestockList";
        $format = Input::query('type');
        $file = Excel::create($filename, function ($excel) {

            // Set the title
            $excel->setTitle('Restocks Done');

            // Chain the setters
            $excel->setCreator('Stock Control System')
                ->setCompany(env('COMPANY_NAME'));

            // Call them separately
            $excel->setDescription('Restocks Done in Weekly and Monthly Period');
            $excel->sheet('Restocks  Done in One Week', function ($sheet) {
                $sheet->freezeFirstRowAndColumn();
                $data = $this->buildReport($this->restock->allFrom(Carbon::now()->subWeek()));
                $sheet->fromArray($data);
            });

            $excel->sheet('Restock Done in one Month', function ($sheet) {
                $sheet->freezeFirstRowAndColumn();
                $data = $this->buildReport($this->restock->allFrom(Carbon::now()->subMonth()));
                $sheet->fromArray($data);
            });


            $excel->sheet('Restocks  Deleted in One Week', function ($sheet) {
                $sheet->freezeFirstRowAndColumn();
                $data = $this->buildReport($this->restock->allDeletedFrom(Carbon::now()->subWeek()));
                $sheet->fromArray($data);
            });

            $excel->sheet('Restocks Deleted in one Month', function ($sheet) {
                $sheet->freezeFirstRowAndColumn();
                $data = $this->buildReport($this->restock->allDeletedFrom(Carbon::now()->subMonth()));
                $sheet->fromArray($data);
            });

        });

        if (Input::has('email')) {
            $email = Input::get('email');
            $save_details = $file->store('xlsx');
            $content = "Please find attached a list of restocked items from the stock control system";
            Mail::send('emails.product', array('content' => $content), function ($message) use ($save_details, $email) {
                $message->to($email)->subject('Restocked Items Report');
                $message->attach($save_details['full']);
            });
            return Redirect::action('RestockController@index');
        } else {
            $file->download($format);
        }
    }

    public function buildReport($data)
    {
        $output = array();


        foreach ($data as $restock) {
            if ($restock->product) {
                $productName = $restock->product->productName;
            } else {
                $productName = "Deleted Product";
            }


            if ($restock->supplier) {
                $supplierName = $restock->supplier->supplierName;
            } else {
                $supplierName = "Deleted Supplier";
            }
            $arr = array(
                'ProductName' => $productName,
                'SupplierName' => $supplierName,
                'Cost' => $restock->unitCost,
                'Amount' => $restock->amount,
                'Remarks' => $restock->remarks,
                'RestockedOn' => Carbon::parse($restock->created_at)->format('d/m/Y H:i')
            );
            array_push($output, $arr);
        }
        return $output;
    }

    public function uploadDocs()
    {
        if (Input::hasFile('file')) {
            $product_image = Input::file('file');
            $destinationPath = Helper::downloadPath() . '/receipts/';
            $filename = str_random(6) . '_' . $product_image->getClientOriginalName();
            $save_path = $destinationPath;
            $product_image->move($save_path, $filename);
            return array('save_path' => $filename);
        }
    }

    public function getDownload()
    {
        $file = Input::query('file');
        $file = Helper::downloadPath() . '/receipts/' . $file;
        return Response::download($file);
    }
}
