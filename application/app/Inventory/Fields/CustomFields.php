<?php namespace Inventory\Fields;

use Inventory\Repository\Model\ModelInterface;
use DB;
use File;
use Schema;

/**
 * Class CustomFields
 * @package Inventory\Fields
 */
class CustomFields implements CustomFieldsInterface
{

    /**
     * @param ModelInterface $model
     */
    public function __construct(ModelInterface $model)
    {
        $this->model = $model;
    }

    /**
     * Gets Existing columns in a table
     * @param $table
     * @return array
     */
    public function getColumns($table)
    {
        return Schema::getColumnListing('users');
    }

    /**
     * Creates a column that does not exist in a table
     * @param $table
     * @param $column
     * @param $columnType
     */
    public function createColumn($table, $column, $columnType)
    {
        Schema::table($table, function ($table) use ($columnType, $column) {
            $x = call_user_func(array($table, $columnType), $column);
            call_user_func(array($x, 'nullable'));
        });
    }

    /**
     * @return array
     * Get Tables
     */
    public function getTables()
    {
        return DB::select('SHOW TABLES');
    }

    /**
     * Creates template
     */
    public function createTemplate()
    {
        $models = ['suppliers', 'restocks', 'products', 'dispatches', 'departments'];
        foreach ($models as $model) {
            $response = $this->model->getCustomFields($model);
            $this->createTableHeader($model, $response);
            $this->createTableFields($model, $response);
            $this->createInputFields($model, $response);
        }

    }

    /**
     * @param $directory
     * @param $model
     * Creates table header
     */
    public function createTableHeader($directory, $model)
    {
        $table_header = View('custom/tableheader')->with(compact('model'))->render();
        $filename = base_path() . "/resources/views/" . $directory . "/custom";
        $filename = $filename . '/tableheader.blade.php';
        $bytes_written = File::put($filename, $table_header);
    }

    /**
     * @param $directory
     * @param $model
     * Create table fields
     */
    public function createTableFields($directory, $model)
    {
        $table_header = View('custom/tablefields')->with(compact('model'))->render();
        $filename = base_path() . "/resources/views/" . $directory . "/custom";
        $filename = $filename . '/tablefields.blade.php';
        $bytes_written = File::put($filename, $table_header);
    }

    /**
     * @param $directory
     * @param $model
     */
    public function createInputFields($directory, $model)
    {
        $table_header = View('custom/inputfields')->with(compact('model'))->render();
        $filename = base_path() . "/resources/views/" . $directory . "/custom";
        $filename = $filename . '/inputfields.blade.php';
        $bytes_written = File::put($filename, $table_header);
    }

    /**
     * @param $table
     * @param $column
     */
    public function deleteColumn($table, $column)
    {
        if (Schema::hasColumn($table, $column)) {
            Schema::table($table, function ($table) use ($column) {
                $table->dropColumn($column);
            });
        }
    }
}
