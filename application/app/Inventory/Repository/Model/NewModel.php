<?php namespace Inventory\Repository\Model;

use App\CustomField;

/**
 * Class NewModel
 * @package Inventory\Repository\Model
 */
class NewModel implements ModelInterface
{

    /**
     * @param $column
     * @return static
     */
    public function saveColumn($column)
    {
        return CustomField::create($column);
    }

    /**
     * @param $table
     * @return mixed
     */
    public function getCustomFields($table)
    {
        return CustomField::where('table', '=', $table)->get();
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return CustomField::all();
    }

    /**
     * @param $id
     * @return int
     */
    public function deleteColumn($id)
    {
        return CustomField::destroy($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getColumnByID($id)
    {
        return CustomField::find($id);
    }
}
