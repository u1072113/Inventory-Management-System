<?php namespace App\Http\Controllers;

use App\Helper;
use App\Http\Requests;
use App\Http\Requests\NewColumnFormRequest;
use Inventory\Fields\CustomFieldsInterface;
use Inventory\Ldap\LdapInterface;
use Inventory\Repository\Model\ModelInterface;
use Illuminate\Http\Request;
use Input;
use Redirect;

class ModelModifyController extends Controller
{

    public function __construct(CustomFieldsInterface $fields, ModelInterface $columns, LdapInterface $ldap)
    {
        $this->fields = $fields;
        $this->columns = $columns;
        $this->ldap = $ldap;

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //  $this->ldap->authenticate("Administrator","P!r@tes");
        //   $this->ldap->getPcs();
        $tables = $this->fields->getTables();
        $fonts = Helper::fontawesomeArray();
        $columns = $this->columns->all();
        return View('models/index')->with(compact('tables'))->with(compact('fonts'))->with(compact('columns'));
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
    public function store(NewColumnFormRequest $column)
    {
        $this->fields->createColumn(Input::get('table'), Input::get('columnName'), Input::get('columnType'));
        $data = Input::all();
        $loop = rtrim(Input::get('table'), "s");
        $loop = rtrim($loop, "e");
        $data['loop'] = $loop;
        $model = $this->columns->saveColumn($data);
        $this->fields->createTemplate();
        return Redirect::action('ModelModifyController@index');

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
     * @param  int $id
     * @return Response
     */
    public function update($id)
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
        $customColumn = $this->columns->getColumnByID($id);
        var_dump($customColumn->table);
        var_dump($customColumn->columnName);
        $this->fields->deleteColumn($customColumn->table, $customColumn->columnName);
        $this->fields->createTemplate();
        $this->columns->deleteColumn($id);
        return Redirect::action('ModelModifyController@index');
    }

}
