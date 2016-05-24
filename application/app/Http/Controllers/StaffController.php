<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Inventory\Repository\Staff\StaffInterface;
use Illuminate\Http\Request;
use Input;
use Redirect;
use Response;

class StaffController extends Controller
{

    public function __construct(StaffInterface $staff)
    {
        $this->staff = $staff;
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
        $users = $this->staff->all(compact('search', 'sort'));
        $message = "List Of Staff";
        return view('staff.index')->with(compact('users', 'message'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('staff.createupdateuser');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request, Requests\StaffFormRequest $data)
    {
        $data = Input::all();
        $this->staff->createStaff($data);
        return Redirect::action('StaffController@index');
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
        $user = $this->staff->getStaffById($id);
        return view('staff.createupdateuser')->with(compact('user'));
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
        $data = Input::all();
        $this->staff->updateStaff($id, $data);
        return Redirect::action('StaffController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->staff->deleteStaff($id);
        return Response::json(['ok' => 'ok']);
    }

    public function getStaff()
    {
        return $this->staff->getStaffJson();
    }

    public function createStaff()
    {
        $staff = $this->staff->createStaff(Input::all());
        return array('id' => $staff->id, 'text' => $staff->name);
    }

}
