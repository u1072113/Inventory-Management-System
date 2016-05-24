<?php namespace Inventory\Repository\Model;

interface ModelInterface
{

    public function saveColumn($column);

    public function getCustomFields($table);

    public function deleteColumn($id);

    public function getColumnByID($id);

    public function all();
}