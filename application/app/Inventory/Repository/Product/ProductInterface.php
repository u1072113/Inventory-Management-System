<?php namespace Inventory\Repository\Product;

interface ProductInterface
{
    public function all(array $params);

    public function allReport();

    public function productsCount();

    public function getOutOfStock();

    public function getLowStock();

    public function productsList();

    public function getProductById($id);

    public function decreaseProduct($count, $id);

    public function increaseProduct($count, $id);

    public function saveProduct($product);

    public function createBarcode($barcode);

    public function createQrcode($qrcode);

    public function updateProduct($id, $product);

    public function deleteProduct($id);

    public function getDeletedProducts();

    public function restoreProduct($id);

    public function getProductChart();

    public function getDeletedProductsReport();

    public function search($item, $search, $table);

    public function getLowStockCount();

    /**
     * Create Product Category
     * @return mixed
     */
    public function createProductCategory(array $product);

    /**
     * Shows Existsing Product Categories
     * @return mixed
     */
    public function getProductCategories();

    /** Get Json Formatted list
     * @return mixed
     */
    public function getProductCategoriesJson();

    /**
     * Delete an existing Product Category
     * @param $id
     * @return mixed
     */
    public function deleteProductCategory($id);

    /**
     * Edit an Existing Category
     * @param $id
     * @param array $update
     * @return mixed
     */
    public function editProductCategory($id, array $update);

    /**
     * Get List Render for Categories
     * @return mixed
     */
    public function getCategoryList();

    public function getProductsAjax();


}
