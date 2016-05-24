<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\DepartmentFormRequest;
use Carbon;
use Inventory\Repository\Department\DepartmentInterface;
use Inventory\Repository\Model\ModelInterface;
use Excel;
use Flash;
use Illuminate\Http\Request;
use Input;
use Mail;
use Redirect;
use Response;
class DepartmentController extends Controller
{

    public function __construct(DepartmentInterface $department, ModelInterface $extraFields)
    {
        $this->department = $department;
        $this->extraFields = $extraFields;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
 
        $sort = Input::only('sortBy', 'direction');
        $page = Input::get('page', 1);
        $search = Input::only('search');
        $departments = $this->department->all(compact('sort', 'page', 'search'));
        $departmentChart = $this->department->getDepartmentChart();
        $message = "All departments and their limit status";
        return View('departments/index')
            ->with(
                compact('departments', 'departmentChart')
            );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View('departments/newdepartment');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(DepartmentFormRequest $department)
    {
        $this->department->addDepartment(Input::all());
        return Redirect::action('DepartmentController@index');
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
        $department = $this->department->getDepartmentByID($id);
        return View('departments/newdepartment')->with(compact('departments'))->with(compact('department'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $this->department->updateDepartment($id, Input::all());
        return Redirect::action('DepartmentController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->department->deleteDepartment($id);
        return Response::json(['ok' => 'ok']);
    }

    /**
     *Get Deleted items
     */
    public function getDeleted()
    {

    }

    /**
     *Restore an item
     */
    public function restore()
    {

    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function export()
    {
        $format = Input::query('type');
        $filename = Carbon::now()->format('Ymd_') . "Department Usage";
        $file = Excel::create($filename, function ($excel) {

            // Set the title
            $excel->setTitle('Department Reports');

            // Chain the setters
            $excel->setCreator('Stock Control System')
                ->setCompany(env('COMPANY_NAME'));

            // Call them separately
            $excel->setDescription('Department Reports by limits');
            $excel->sheet('Dispatch Done in One Week', function ($sheet) {
                $sheet->freezeFirstRowAndColumn();
                $data = $this->buildReport($this->department->getDepartmentReport());
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
            return Redirect::action('DepartmentController@index');
        } else {
            $file->download($format);
        }
    }

    public function buildReport($data)
    {
        $output = array();
        foreach ($data as $department) {
            $data = array(
                'DepartmentName' => $department->name,
                "BudgetLimit" => $department->budgetLimit,
                "DepartmentUse" => $department->dispatchsum,
                "DepartmentEmail" => $department->departmentEmail,
                "BudgetStartDate" => Carbon::parse($department->budgetStartDate)->format('d/m/Y H:i'),
                "BudgetEndDate" => Carbon::parse($department->budgetEndDate)->format('d/m/Y H:i'),
            );
            array_push($output, $data);
        }
        return $output;
    }
}
