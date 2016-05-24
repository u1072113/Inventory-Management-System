<?php namespace Inventory\Repository\Restock;

use App\Restock;
use Carbon\Carbon;
use Inventory\Repository\Product\ProductInterface;
use Inventory\Repository\Setting\SettingsInterface;

/**
 * Class RestockItem
 * @package Inventory\Repository\Restock
 */
class RestockItem implements RestockInterface
{


    /**
     * @param ProductInterface $product
     */
    public function __construct(ProductInterface $product, SettingsInterface $setting)
    {
        $this->product = $product;
        $this->setting = $setting;

    }

    /**
     * @param array $params
     * @return mixed
     * Gets all restocked items
     */
    public function all(array $params)
    {
        $pagination = $this->setting->getSettings()->paginationDefault;
        $restock = new Restock();
        $restock = $restock->join('products', 'products.id', '=', 'restocks.productID')
            ->join('suppliers', 'suppliers.id', '=', 'restocks.supplierID')
            ->select('restocks.*');

        //Filter
        if ($params['sort']['sortBy'] and $params['sort']['direction']) {
            return Restock::with('product')
                ->with('supplier')
                ->join('products', 'products.id', '=', 'restocks.productID')
                ->join('suppliers', 'suppliers.id', '=', 'restocks.supplierID')
                ->select('restocks.*')
                ->orderBy($params['sort']['sortBy'], $params['sort']['direction'])
                ->paginate($pagination)->setPath('');

        }

        //Search
        if ($params['search']['search']) {
            $restock = $this->search($restock, $params['search']['search'], 'restocks');
        }

        return $restock
            ->with('product')
            ->with('supplier')
            ->with('user')
            ->orderBy('created_at', 'DESC')
            ->paginate($pagination)
            ->setPath('');
    }

    /**
     * @return mixed
     * Gets all defective items
     */
    public function getDefective()
    {
        $pagination = $this->setting->getSettings()->paginationDefault;
        return Restock::join('products', 'products.id', '=', 'restocks.productID')
            ->onlyTrashed()
            ->where('restocks.isDamagedReturned', '=', 1)
            ->join('suppliers', 'suppliers.id', '=', 'restocks.supplierID')
            ->select('restocks.*')
            ->with('product')
            ->with('supplier')
            ->with('user')
            ->orderBy('created_at', 'DESC')
            ->paginate($pagination);
    }

    /**
     * @param $item
     * @param $search
     * @param $table
     * @return mixed
     * Used to search restocks
     */
    public function search($item, $search, $table)
    {
        $columns = ['products.productName', 'suppliers.supplierName'];

        $item = $item->where(function ($query) use ($search, $columns) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', "%{$search}%");
            }
        });
        return $item;
    }

    /**
     * @param $date
     * @return mixed
     * Used to get suppliers from a certain date
     */
    public function allFrom($date)
    {
        return Restock::with('product')
            ->with('supplier')
            ->where('created_at', '>', $date)
            ->get();
    }

    /**
     * @param $date
     * @return mixed
     * Used to get deleted supliers from a certain date
     */
    public function allDeletedFrom($date)
    {
        return Restock::onlyTrashed()
            ->with('product')
            ->with('supplier')
            ->where('created_at', '>', $date)
            ->get();
    }

    /**
     * @return mixed
     * used to get all deleted suppliers
     */
    public function getDeleted()
    {
        $pagination = $this->setting->getSettings()->paginationDefault;
        return Restock::onlyTrashed()
            ->with('product')
            ->with('supplier')
            ->with('user')
            ->orderBy('created_at', 'DESC')
            ->paginate($pagination)
            ->setPath('');
    }

    /**
     * Records a new restock of a product in db
     * @param $product
     * @return static
     */
    public function restock($product)
    {
        $this->product->increaseProduct($product['amount'], $product['productID']);
        $this->product->updateProduct($product['productID'], array('unitCost' => $product['unitCost']));
        return Restock::create($product);
    }

    /**
     * Deletes a restock and decreases amount of an item in the inventory list
     * @param $id
     * @param $product
     */
    public function delete($id, $product)
    {
        $restock = $this->getById($id);
        $this->product->decreaseProduct($restock->amount, $restock->productID);
        $restock->update($product);
        Restock::destroy($id);
    }

    /**
     * Helper Function to get restock by id
     * @param $id
     * @return \Illuminate\Support\Collection|null|static
     */
    public function getById($id)
    {
        return Restock::find($id);
    }

    /**
     * Updates a resock of an item it first decreases the amount then adds the correct amount
     * @param $id
     * @param $product
     */
    public function updateRestock($id, $product)
    {
        $restock = Restock::find($id);
        $this->product->decreaseProduct($restock->amount, $restock->productID);
        $this->product->increaseProduct($product['amount'], $restock->productID);
        $restock->update($product);
    }

    public function getRestocksCount()
    {
        return Restock::all()->count();
    }

    /**
     * @return mixed
     * Get count of all deleted restocks
     */
    public function getDeletedRestocksCount()
    {
        return Restock::onlyTrashed()->count();
    }

    /**
     * Restore a deleted Restock
     * @param $id
     * @return mixed
     */
    public function restoreRestock($id)
    {
        return Restock::withTrashed()->where('id', $id)->restore();
    }

    /**
     * @param $days
     * @return mixed
     * Get cost for dashboard
     */
    public function getCost($days)
    {
        return Restock::where('created_at', '>', Carbon::today()->subMonth())->sum('itemCost');
    }
}
