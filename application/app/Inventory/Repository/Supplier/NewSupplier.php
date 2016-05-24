<?php namespace Inventory\Repository\Supplier;

use App\Helper;
use App\Supplier;
use Inventory\Repository\Setting\SettingsInterface;
use Schema;

/**
 * Class NewSupplier
 * @package Inventory\Repository\Supplier
 */
class NewSupplier implements SupplierInterface
{
    public function __construct(SettingsInterface $setting)
    {
        $this->setting = $setting;
    }
    /**
     * @param array $params
     * @return $this
     */
    public function all(array $params)
    {
        $pagination = $this->setting->getSettings()->paginationDefault;
        //Filter
        if ($params['sort']['sortBy'] and $params['sort']['direction']) {
            $supplier = Supplier::leftJoin('restocks', 'suppliers.id', '=', 'restocks.supplierID')
                ->selectRaw('suppliers.* , count(restocks.id) as restockscount, sum(restocks.unitCost) as restockssum')
                ->groupBy('suppliers.id')
                ->orderBy($params['sort']['sortBy'], $params['sort']['direction'])
                ->skip($pagination * ($params['page'] - 1))
                ->take($pagination)
                ->get();
            return Helper::paginateQuery(
                $supplier,
                $this->getSuppliersCount(),
                $pagination,
                null,
                $params['sort'],
                '/supplier'
            )->setPath('');
        }
        //Search
        $supplier = new Supplier();

        $supplier = $supplier->leftJoin('restocks', 'suppliers.id', '=', 'restocks.supplierID')
            ->selectRaw('suppliers.* , count(restocks.id) as restockscount, sum(restocks.unitCost) as restockssum')
            ->groupBy('suppliers.id');

        //Search
        if ($params['search']['search']) {
            $supplier = $this->search($supplier, $params['search']['search'], 'suppliers');
        }

        $count = count($supplier->get());
        $supplier = $supplier->skip($pagination * ($params['page'] - 1))
            ->take($pagination)
            ->get();
        return Helper::paginateQuery(
            $supplier,
            $count,
            $pagination,
            null,
            $params['search'],
            '/supplier'
        )->setPath('');
    }

    /**
     * @param $item
     * @param $search
     * @param $table
     * @return mixed
     * Used for searching Suppliers
     */
    public function search($item, $search, $table)
    {
        $columns = Schema::getColumnListing($table);
        unset($columns[0]);
        $first = $columns[1];
        $item = $item->where(function ($query) use ($search, $columns) {
            foreach ($columns as $column) {
                $query->orWhere('suppliers.'.$column, 'LIKE', "%{$search}%");
            }
        });
        return $item;
    }

    public function allSuppliersReport()
    {
        return Supplier::leftJoin('restocks', 'suppliers.id', '=', 'restocks.supplierID')
            ->selectRaw('suppliers.supplierName ,suppliers.phone,suppliers.website,suppliers.email,suppliers.supplierDiscount, count(restocks.id) as restockscount, sum(restocks.unitCost) as restockssum,suppliers.created_at as SupplierSince')
            ->groupBy('suppliers.id')
            ->get(['restockscount']);
    }

    public function allDeletedSuppliersReport()
    {
        //return Supplier::paginate(env('RECORDS_VIEW'));
        return Supplier::leftJoin('restocks', 'suppliers.id', '=', 'restocks.supplierID')
            ->selectRaw('suppliers.* , count(restocks.id) as restockscount, sum(restocks.unitCost) as restockssum')
            ->groupBy('suppliers.id')
            ->onlyTrashed()
            ->get();
    }

    public function getDeletedSuppliers()
    {
        //return Supplier::paginate(env('RECORDS_VIEW'));
        $pagination = $this->setting->getSettings()->paginationDefault;
        return Supplier::leftJoin('restocks', 'suppliers.id', '=', 'restocks.supplierID')
            ->selectRaw('suppliers.* , count(restocks.id) as restockscount, sum(restocks.unitCost) as restockssum')
            ->groupBy('suppliers.id')
            ->onlyTrashed()
            ->paginate($pagination)->setPath('');
    }

    /**
     * Returns Supplier list for use in select list
     * @return mixed
     */
    public function supplierList()
    {
        return Supplier::lists('supplierName', 'id');
    }

    /**
     * @return mixed
     */
    public function getSuppliersCount()
    {
        return Supplier::all()->count();
    }

    /**
     * @return mixed
     */
    public function getDeletedSuppliersCount()
    {
        return Supplier::onlyTrashed()->count();
    }

    /**
     * @param $id
     * @return int
     */
    public function deleteSupplier($id)
    {
        return Supplier::destroy($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function restoreSupplier($id)
    {
        return Supplier::withTrashed()->where('id', $id)->restore();
    }

    /**
     * @param $supplier
     * @return static
     */
    public function createSupplier($supplier)
    {
        return Supplier::create($supplier);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getSupplierById($id)
    {
        return Supplier::find($id);
    }

    /**
     * @param $id
     * @param $newSupplier
     */
    public function updateSupplier($id, $newSupplier)
    {
        Supplier::withTrashed()->find($id)->update($newSupplier);
    }

    /**
     * @return mixed
     */
    public function suppliersReportAmount()
    {
        return Supplier::join('restocks', 'suppliers.id', '=', 'restocks.supplierID', 'inner')
            ->selectRaw('suppliers. supplierName as label, CAST(sum(restocks.unitCost) as UNSIGNED) as value')
            ->groupBy('suppliers.id')
            ->orderBy('value')
            ->get();
    }
}
