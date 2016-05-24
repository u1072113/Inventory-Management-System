<?php namespace App\Http\Controllers;

use App\Company;
use App\Country;
use App\Http\Requests;
use Auth;
use Inventory\Repository\Setting\SettingsInterface;
use Input;
use Redirect;

class SettingController extends Controller
{
    /**
     * @var SettingsInterface
     */
    private $setting;

    /**
     * @param SettingsInterface $setting
     */
    function __construct(SettingsInterface $setting)
    {
        $this->setting = $setting;
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
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
        $settings = $this->setting->getSettings();
        $company = Company::find(Auth::user()->companyId);
        $countries = Country::all()->lists('country', 'country');
        $currency = Country::all()->lists('currency', 'currency');
        return view('settings/userSettings')->with(compact('settings', 'currency', 'countries', 'company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $this->setting->setSettings(Input::except(['_method', '_token']));
        return Redirect::action('SettingController@edit', array(Auth::user()->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function updateAjax($id)
    {
        $this->setting->setSettings(Input::except(['_method', '_token']));
        return response()->json('okay');
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
