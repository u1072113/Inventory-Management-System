<?php namespace Inventory\Repository\Dispatch;

/**
 * Interface DispatchInterface
 * @package Inventory\Repository\Dispatch
 */
interface DispatchInterface
{
    public function all(array $params);

    public function allFrom($date);

    public function allDeletedFrom($date);

    public function dispatch($product);

    public function delete($id, $product);

    public function getById($id);

    public function updateDispatch($id, $product);

    public function getDeletedDispatch();

    public function restoreDispatch($id);

    public function getDispatchCount();

    public function getDeletedCount();

    public function getDailyDispatchReport();

    public function getMonthlyDispatchReport();

    public function getDefective();

    public function getCost();
}
