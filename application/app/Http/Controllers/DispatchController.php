<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\DispatchFormRequest;
use Carbon;
use Clockwork;
use Inventory\Repository\Dispatch\DispatchInterface;
use Inventory\Repository\Product\ProductInterface;
use Inventory\Repository\User\UserInterface;
use Excel;
use Flash;
use Illuminate\Http\Request;
use Input;
use Mail;
use Redirect;
use Response;

class DispatchController extends Controller
{

    public function __construct(
        ProductInterface $product,
        UserInterface $user,
        DispatchInterface $dispatch
    )
    {
        $this->product = $product;
        $this->user = $user;
        $this->dispatch = $dispatch;
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
        $dispatchedItems = $this->dispatch->all(compact('sort', 'search'));
        $dispatchTrend = $this->dispatch->getDailyDispatchReport();
        $message = "Dispatched Items Recent First";
        return View('dispatches/index')->with(compact('dispatchedItems'))->with('dispatchTrend',
            json_encode($dispatchTrend));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View('dispatches/dispatch');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(DispatchFormRequest $validate)
    {
        //
        $this->dispatch->Dispatch(Input::all());
        Flash::message('Dispatch was successful', 'info');
        return Redirect::action('DispatchController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $dispatch = $this->dispatch->getById($id);
        $view_string = View('dispatches.partials.popupview')->with(compact('dispatch'))->render();
        return $view_string;
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
        $dispatch = $this->dispatch->getById($id);
        return View('dispatches/dispatch')
            ->with(compact('dispatch'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id, DispatchFormRequest $validate)
    {
        //
        $this->dispatch->updateDispatch($id, Input::all());
        return Redirect::action('DispatchController@index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->dispatch->delete($id, Input::all());
        return Response::json(['ok' => 'ok']);
    }

    /**
     * @return $this
     */
    public function getDeleted()
    {
        $dispatchedItems = $this->dispatch->getDeletedDispatch();
        $restore = 1;
        $message = "Deleted Dispatches";
        return View('dispatches/index')->with(compact('dispatchedItems', 'restore', 'message'));
    }

    /**
     * @return $this
     */
    public function getDefective()
    {
        $dispatchedItems = $this->dispatch->getDefective();
        $restore = 1;
        $message = "Deleted items that have been marked as defective";
        return View('dispatches/index')->with(compact('dispatchedItems', 'restore', 'message'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        $this->dispatch->restoreDispatch($id);
        return Redirect::action('DispatchController@index');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function export()
    {

        $format = Input::query('type');
        $filename = Carbon::now()->format('Ymd_') . "DispatchList";
        $file = Excel::create($filename, function ($excel) {

            // Set the title
            $excel->setTitle('Dispatch Done');

            // Chain the setters
            $excel->setCreator('Stock Control System')
                ->setCompany(env('COMPANY_NAME'));

            // Call them separately
            $excel->setDescription('Dispatches Done in Weekly and Monthly Period');
            $excel->sheet('Dispatch Done in One Week', function ($sheet) {
                $sheet->freezeFirstRowAndColumn();
                $data = $this->buildReport($this->dispatch->allFrom(Carbon::now()->subWeek()));
                $sheet->fromArray($data);
            });

            $excel->sheet('Dispatches Done in one Month', function ($sheet) {
                $sheet->freezeFirstRowAndColumn();
                $data = $this->buildReport($this->dispatch->allFrom(Carbon::now()->subMonth()));
                $sheet->fromArray($data);
            });


            $excel->sheet('Dispatches  Deleted in One Week', function ($sheet) {
                $sheet->freezeFirstRowAndColumn();
                $data = $this->buildReport($this->dispatch->allDeletedFrom(Carbon::now()->subWeek()));
                $sheet->fromArray($data);
            });

            $excel->sheet('Dispatches Deleted in one Month', function ($sheet) {
                $sheet->freezeFirstRowAndColumn();
                $data = $this->buildReport($this->dispatch->allDeletedFrom(Carbon::now()->subMonth()));
                $sheet->fromArray($data);
            });

        });

        if (Input::has('email')) {
            $email = Input::get('email');

            $save_details = $file->store('xlsx');

            $content = "Please find attached a list of dispatched items from the stock control system";
            Mail::send('emails.product', array('content' => $content), function ($message) use ($save_details, $email) {
                $message->to($email)->subject('Dispatched Items Report');
                $message->attach($save_details['full']);
            });
            return Redirect::action('DispatchController@index');
        } else {
            $file->download($format);
        }
    }

    /**
     * @param $data
     * @return array
     */
    public function buildReport($data)
    {
        $output = array();
        foreach ($data as $dispatch) {
            if ($dispatch->product) {
                $productName = $dispatch->product->productName;
            } else {
                $productName = "Deleted Product";
            }
            if ($dispatch->user) {
                $userName = $dispatch->user->name;
            } else {
                $userName = "Deleted User";
            }
            $dispatch = array(
                'ProductName' => $productName,
                "Dispatched To" => $userName,
                "Amount Dispatched" => $dispatch->amount,
                "remarks" => $dispatch->remarks,
                "Dispatched On" => Carbon::parse($dispatch->created_at)->format('d/m/Y H:i')
            );
            array_push($output, $dispatch);
        }
        return $output;
    }
}
