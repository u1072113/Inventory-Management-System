<?php namespace Inventory\Repository\Dispatch;

use App\Dispatch;
use Carbon;
use Inventory\Repository\Product\ProductInterface;
use Inventory\Repository\Setting\SettingsInterface;
use Inventory\Repository\Staff\StaffInterface;
use DB;

/**
 * Class DispatchItem
 * @package Inventory\Repository\Dispatch
 */
class DispatchItem implements DispatchInterface
{


    /**
     * @param ProductInterface $product
     * @param StaffInterface $user
     */
    public function __construct(ProductInterface $product, StaffInterface $staff, SettingsInterface $setting)
    {
        $this->product = $product;
        $this->staff = $staff;
        $this->setting = $setting;
    }

    /**
     * @param array $params
     * @return mixed
     * Returns all paginated dispatches
     */
    public function all(array $params)
    {
        $pagination = $this->setting->getSettings()->paginationDefault;
        $dispatch = new Dispatch();
        $dispatch = $dispatch->join('products', 'products.id', '=', 'dispatches.dispatchedItem')
            ->join('staff', 'staff.id', '=', 'dispatches.dispatchedTo')
            ->join('departments', 'departments.id', '=', 'dispatches.departmentId', 'left')
            ->select('dispatches.*', 'dispatches.created_at as dipatched_at');

        //Filter
        if ($params['sort']['sortBy'] and $params['sort']['direction']) {
            return Dispatch::with('product')
                ->with('staff')
                ->join('products', 'products.id', '=', 'dispatches.dispatchedItem')
                ->join('staff', 'staff.id', '=', 'dispatches.dispatchedTo')
                ->select('dispatches.*', 'dispatches.created_at as dipatched_at')
                ->orderBy($params['sort']['sortBy'], $params['sort']['direction'])
                ->paginate($pagination)->setPath('');

        }
        //Search
        if ($params['search']['search']) {
            $dispatch = $this->search($dispatch, $params['search']['search']);
        }

        return $dispatch
            ->with('product')
            ->with('staff')
            ->orderBy('dispatches.created_at', 'DESC')
            ->paginate($pagination)
            ->setPath('');
    }

    /**
     * @param $item
     * @param $search
     * @return mixed used to search for a dispatch
     * used to search for a dispatch
     * @internal param $table
     */
    public function search($item, $search)
    {
        $columns = [
            'products.productName',
            'staff.name',
            'dispatches.amount',
            'dispatches.totalCost',
            'departments.name'
        ];
        $first = $columns[1];
        unset($columns[1]);
        $item = $item->where($first, 'LIKE', "%{$search}%");
        foreach ($columns as $column) {
            $item = $item->orWhere($column, 'LIKE', "%{$search}%");
        }
        return $item;
    }

    /**
     * @param $date
     * @return mixed
     * Gets all dispatch from a certain date
     */
    public function allFrom($date)
    {
        return Dispatch::with('product')
            ->with('staff')
            ->orderBy('created_at', 'DESC')
            ->where('created_at', '>', $date)
            ->get();
    }

    /**
     * @param $date
     * @return mixed
     * Gets all deleted dispatch from a certain date
     */
    public function allDeletedFrom($date)
    {
        return Dispatch::onlyTrashed()
            ->with('product')
            ->with('staff')
            ->orderBy('created_at', 'DESC')
            ->where('created_at', '>', $date)
            ->get();
    }

    /**
     * @return mixed
     * Gets all deleted dispatch
     */
    public function getDeletedDispatch()
    {
        $pagination = $this->setting->getSettings()->paginationDefault;
        return Dispatch::with('product')
            ->with('staff')->onlyTrashed()
            ->orderBy('deleted_at', 'DESC')
            ->paginate($pagination)->setPath('');
    }

    /**
     * @return mixed
     * Gets all defective dispatch
     */
    public function getDefective()
    {
        $pagination = $this->setting->getSettings()->paginationDefault;
        return Dispatch::with('product')
            ->with('staff')->onlyTrashed()
            ->orderBy('deleted_at', 'DESC')
            ->where('isReturned', '=', 1)
            ->paginate($pagination)->setPath('');
    }

    /**
     * @return mixed
     * Gets all non-deleted dispatch count
     */
    public function getDispatchCount()
    {
        return Dispatch::with('product')->with('staff')->count();
    }

    /**
     * @return mixed
     * Gets all deleted dispatches
     */
    public function getDeletedCount()
    {
        return Dispatch::onlyTrashed()->count();
    }

    /**
     * Dispatch Item to Employee reduces the amount in the inventory List
     * @param $product
     * @return static
     */
    public function dispatch($product)
    {
        $this->product->decreaseProduct($product['amount'], $product['dispatchedItem']);
        $stock_item = $this->product->getProductById($product['dispatchedItem']);
        $product["categoryName"] = $stock_item->categoryName;
        $product["categoryId"] = $stock_item->categoryId;
        $product['totalCost'] = $stock_item->unitCost * $product['amount'];
        $product['departmentId'] = $this->staff->getStaffById($product['dispatchedTo'])->departmentId;
        return Dispatch::create($product);
    }

    /**
     * Deletes a Dispatch from the inventory and returns the item to the inventory
     * @param $id
     * @param $product
     * @return int
     */
    public function delete($id, $product)
    {
        $dispatch = $this->getById($id);
        $this->product->increaseProduct($dispatch->amount, $dispatch->dispatchedItem);
        $dispatch->update($product);
        return Dispatch::destroy($id);
    }

    /**
     * Updates an existing dispatch in the dispatch
     * It first returns the Item to the Inventory List
     * And then dispatches it a new
     * @param $id
     * @param $product
     * @return mixed|void
     */
    public function updateDispatch($id, $product)
    {
        $updateDispatch = Dispatch::find($id);
        $this->product->increaseProduct($updateDispatch->amount, $updateDispatch->dispatchedItem);
        $this->product->decreaseProduct($product['amount'], $updateDispatch->dispatchedItem);
        $updateDispatch->update($product);
    }

    /**
     * Gets a dispatch by ID
     * @param $id
     * @return \Illuminate\Support\Collection|null|static
     */
    public function getById($id)
    {
        return Dispatch::withTrashed()->find($id);
    }

    /**
     * Restore a deleted Dispatch
     * @param $id
     * @return mixed
     */
    public function restoreDispatch($id)
    {
        return Dispatch::withTrashed()->where('id', $id)->restore();
    }

    /**
     * @return mixed
     * gets all dispatch for today
     */
    public function getDailyDispatchReport()
    {
        $x = array(DB::raw('sum(dispatches.amount)as dispatchcount'), DB::raw('DATE(dispatches.created_at) day'));
        return Dispatch::leftJoin('products', 'dispatches.dispatchedItem', '=', 'products.id')
            // ->select(DB::raw('DATE(dispatches.created_at),sum(dispatches.amount) as dispatchcount'))
            ->select($x)
            ->groupBy(DB::raw('DATE(dispatches.created_at)'))
            ->get();
    }

    public function getMonthlyDispatchReport()
    {
        $x = array(
            'products.productName',
            DB::raw('sum(dispatches.amount)as dispatchcount'),
            DB::raw('MONTH(dispatches.created_at) month')
        );
        return Dispatch::leftJoin('products', 'dispatches.dispatchedItem', '=', 'products.id')
            // ->select(DB::raw('DATE(dispatches.created_at),sum(dispatches.amount) as dispatchcount'))
            ->select($x)
            ->groupBy('products.productName', DB::raw('MONTH(dispatches.created_at)'))
            ->orderBy('month')
            ->get();
    }

    public function getCost()
    {
        //dd(Carbon::today()->subMonth());
        return Dispatch::where('created_at', '>', Carbon::today()->subMonth())->sum('totalCost');
    }
}
