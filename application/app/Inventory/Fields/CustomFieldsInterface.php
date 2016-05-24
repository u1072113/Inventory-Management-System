<?php namespace Inventory\Fields;

interface CustomFieldsInterface
{
    public function getColumns($table);

    public function createColumn($table, $column, $columnType);

    public function deleteColumn($table, $column);

    public function getTables();

    public function createTemplate();
}
