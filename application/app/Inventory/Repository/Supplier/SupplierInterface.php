<?php namespace Inventory\Repository\Supplier;

interface SupplierInterface
{
    public function all(array $params);

    public function allSuppliersReport();

    public function allDeletedSuppliersReport();

    public function supplierList();

    public function suppliersReportAmount();

    public function createSupplier($supplier);

    public function deleteSupplier($id);

    public function restoreSupplier($id);

    public function getSuppliersCount();

    public function getDeletedSuppliersCount();

    public function getDeletedSuppliers();

    public function getSupplierById($id);

    public function updateSupplier($id, $newSupplier);
}
