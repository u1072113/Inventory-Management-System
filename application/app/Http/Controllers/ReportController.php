<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use DB;
use Excel;
use Illuminate\Http\Request;
use Input;
use App\Helper;
use Carbon;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('Reports/index');
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
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = DB::table(Input::get('reportType'))
            ->get();
        $data = Helper::objectToArray($data);
        $filename = Carbon::now()->format('Ymd_') . "ProductsList";
        $file = Excel::create($filename, function ($excel) use ($data) {

            // Set the title
            $excel->setTitle('Products List and their Levels');

            // Chain the setters
            $excel->setCreator('Stock Control System')
                ->setCompany(env('COMPANY_NAME'));

            // Call them separately
            $excel->setDescription('Products List and their Levels');
            $excel->sheet('Existing Products in Stock', function ($sheet) use ($data) {
                $sheet->freezeFirstRowAndColumn();
                $sheet->fromModel($data);
            });
        });
        //fileType
        $file->download('xlsx');
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
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
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
