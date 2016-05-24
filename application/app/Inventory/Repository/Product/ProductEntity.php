<?php namespace Inventory\Repository\Product;

use App\Product;
use App\ProductCategory;
use Inventory\Repository\Setting\SettingsInterface;
use Schema;
use DB;
use DNS1D;
use DNS2D;

/**
 * Class StockItem
 * @package Inventory\Repository\Product
 * Repository for Product Item
 */
class ProductEntity implements ProductInterface
{
    public function __construct(SettingsInterface $setting)
    {
        $this->setting = $setting;
    }

    /**
     * @param array $params
     * @return mixed
     * Get all products
     */
    public function all(array $params)
    {
        $pagination = $this->setting->getSettings()->paginationDefault;
        //Filter
        if ($params['sort']['sortBy'] and $params['sort']['direction']) {
            return Product::orderBy(
                $params['sort']['sortBy'],
                $params['sort']['direction']
            )->paginate(
                $pagination
            )->setPath('');
        }
        $product = new Product();
        //Search
        if ($params['search']['search']) {
            $product = $this->search($product, $params['search']['search'], 'products');
        }

        return $product->orderBy('created_at', 'DESC')->paginate($pagination)->setPath('');
    }

    /**
     * @param $item
     * @param $search
     * @param $table
     * @return mixed
     * Used to search for products
     */
    public function search($item, $search, $table)
    {
        $columns = Schema::getColumnListing($table);
        unset($columns[0]);
        $item = $item->where(function ($query) use ($search, $columns) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', "%{$search}%");
            }
        });
        return $item;
    }

    /**
     * @return mixed
     * Used to get all items for excel report
     */
    public function allReport()
    {
        return Product::all();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     * Gets all deleted products for excel report
     */
    public function getDeletedProductsReport()
    {
        return Product::onlyTrashed()->get();
    }

    /**
     * @return mixed
     * Gets all paginated products for web page
     */
    public function getDeletedProducts()
    {
        $pagination = $this->setting->getSettings()->paginationDefault;
        return Product::onlyTrashed()->paginate($pagination)->setPath('');
    }

    /**
     * @return mixed
     * Gets product Count
     */
    public function productsCount()
    {
        return Product::count();
    }

    /**
     * Saves the item to the inventory list (db)
     * @param $product
     * @return static
     */
    public function saveProduct($product)
    {
        //Create Barcode
        $product['barcodeFileName'] = $this->createBarcode($product['barcode']);
        //Create QRCode
        $product['qrcodeFileName'] = $this->createQrcode($product['qrcode']);

        return Product::create($product);
    }

    /**
     * Creates the Barcode and returns the image name
     * @param $barcode
     * @return string
     */
    public function createBarcode($barcode)
    {
        if ($barcode == "") {
            return null;
        }
        $path = basename(
            DNS1D::getBarcodePNGPath(
                $barcode,
                env('BARCODE', 'c128'),
                env('BARCODE_WIDTH', 2),
                env('BARCODE_LENGTH', 30)
            )
        );
        return $path;
    }

    /**
     * Creates QRCode and Returns image name
     * @param $qrcode
     * @return string
     */
    public function createQrcode($qrcode)
    {
        if ($qrcode == "") {
            return null;
        }
        $path = basename(
            DNS2D::getBarcodePNGPath(
                $qrcode,
                "QRCODE",
                env('QRCODE_WIDTH', 2),
                env('QRCODE_LENGTH', 30)
            )
        );
        return $path;
    }


    /**
     * Gets all products for use in a select
     * @return mixed
     */
    public function productsList()
    {
        return Product::select(DB::raw('concat(productName, " (", IFNULL(amount, 0), ")") as cow,id'))->lists('cow', 'id');
    }

    /**
     * Returns product by id
     * @param $id
     * @return mixed
     */
    public function getProductById($id)
    {
        return Product::with('restocks')->find($id);
    }

    /**
     * Decreases amount of an item in inventory by $count
     * @param $count
     * @param $id
     */
    public function decreaseProduct($count, $id)
    {
        $product = Product::find($id);
        if ($product->amount - $count < 0) {
            $product->amount = 0;
            $product->save();
        } else {
            $product->decrement('amount', $count);
        }

    }

    /**
     * Increases amount of an item in inventory by $count
     * @param $count
     * @param $id
     */
    public function increaseProduct($count, $id)
    {
        $product = Product::find($id);
        $product->increment('amount', $count);
    }

    /**
     * Get count of  Items that are out of stock
     * @return mixed
     */
    public function getOutOfStock()
    {
        $pagination = $this->setting->getSettings()->paginationDefault;
        return Product::whereRaw('amount = 0 or amount IS NULL')->paginate($pagination)->setPath('');
    }

    /**
     * Get Items that are low on stock
     * @return mixed
     */
    public function getLowStock()
    {
        $pagination = $this->setting->getSettings()->paginationDefault;
        return Product::whereRaw('amount < reorderAmount and amount > 0')->paginate($pagination)->setPath('');
    }

    /**
     * @param $id
     * @return int
     * Used to delete a product
     */
    public function deleteProduct($id)
    {
        return Product::destroy($id);
    }

    /**
     * @param $id
     * @return mixed
     * Used to restore a deleted product
     */
    public function restoreProduct($id)
    {
        return Product::withTrashed()->where('id', $id)->restore();
    }

    /**
     * @param $id
     * @param $product
     * @return mixed
     * Used to update an existing product
     */
    public function updateProduct($id, $product)
    {
        if (isset($product['barcode']) and isset($product['qrcodeFileName'])) {
            //Update Barcode
            $product['barcodeFileName'] = $this->createBarcode($product['barcode']);
            //Update QRCode
            $product['qrcodeFileName'] = $this->createQrcode($product['qrcode']);
        }
        return Product::find($id)->update($product);
    }

    /**
     * @return mixed
     * Used to get json data for report
     */
    public function getProductChart()
    {
        return Product::select('productName', 'amount', 'reorderAmount')->get();
    }

    /**
     * Get Items that are low on stock
     * @return mixed
     */
    public function getLowStockCount()
    {
        return Product::whereRaw('amount < reorderAmount and amount > 0')->count();
    }

    /**
     * Create Product Category
     * @return mixed
     */
    public function createProductCategory(array $product)
    {
        return ProductCategory::create($product);
    }


    /**
     * Shows Existsing Product Categories
     * @return mixed
     */
    public function getProductCategories()
    {
        $pagination = $this->setting->getSettings()->paginationDefault;
        return ProductCategory::paginate($pagination)->setPath('');
    }

    /** Get Json Formatted list
     * @return mixed
     */
    public function getProductCategoriesJson()
    {
        return ProductCategory::select(DB::raw('categoryName as text,id'))->get();
    }


    /**
     * Delete an existing Product Category
     * @param $id
     * @return mixed
     */
    public function deleteProductCategory($id)
    {
        return ProductCategory::destroy($id);
    }

    /**
     * Edit an Existing Category
     * @param $id
     * @param array $update
     * @return mixed
     */
    public function editProductCategory($id, array $update)
    {
        return ProductCategory::find($id)->update($update);
    }

    /**
     * Get List Render for Categories
     * @return mixed
     */
    public function getCategoryList()
    {
        ProductCategory::all()->lists('categoryName', 'id');
    }

    public function getProductsAjax()
    {
        return Product::all();
    }


}
