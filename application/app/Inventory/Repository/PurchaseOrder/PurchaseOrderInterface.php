<?php
namespace Inventory\Repository\PurchaseOrder;


interface PurchaseOrderInterface
{

    /**
     * Add a purchase Order
     * @param $purchaseOrder
     * @return mixed
     * @internal param $supplierId
     * @internal param array $order
     * @internal param int $isFavourite
     * @internal param int $isEmail
     */
    public function addPurchaseOrder($purchaseOrder);

    /**
     * Update Existing Purchase Order
     * @param $purchaseId
     * @param $purchaseOrder
     */
    public function updatePurchaseOrder($purchaseId, $purchaseOrder);

    /**
     * Generate PDF
     * @param $purchaseOrderId
     */
    public function generatePdf($purchaseOrderId);

    /**
     * Send Email with Purchase Order
     * @param $supplierId
     * @param $orderId
     */
    public function sendEmail($supplierId, $orderId);

    /**
     * Update delivery of received items
     * @param $purchaseOrderId
     */
    public function updateDelivery($purchaseOrderId);

    /**
     * Get Delivery Count of purchase order
     * @param $orderId
     * @return array
     */
    public function getDeliveryCount($orderId);

    /**
     * Get list of purchase orders
     * @param array $params
     * @return mixed
     */
    public function getPurchaseOrders(array $params);

    /**
     * @param $item
     * @param $search
     * @param $table
     * @return mixed
     * Used to search for products
     */
    public function search($item, $search, $table);

    /**
     * Get Purchase Order
     * @param $purchaseOrderId
     * @return mixed
     */
    public function getPurchaseOrder($purchaseOrderId);


    /**
     * Deletes Purchase Order
     * @param $purchaseOrderId
     */
    public function deletePurchaseOrder($purchaseOrderId);

    /**
     * Get number of purchase orders
     * @return int
     */
    public function getPurchaseOrderCount();

    /**
     * Suggest Purchase Order Items
     * @return mixed
     */
    public function suggestPurchaseOrder();

    /**
     * Gets all products for use in a select
     * @return mixed
     */
    public function autoSuggestList();

    /**
     * Get one product with restock suggestion
     * @param $purchaseOrderId
     * @return mixed
     */
    public function getProductWithRestockSuggestion($purchaseOrderId);

    /**
     * Restock from purchase Order
     * @param $product
     */
    public function restockFromPurchaseOrder($product);

    /**
     * Generate LPOs PDF
     * @return mixed
     */
    public function lpoReport();

    /**
     * Restore a deleted item
     * @return mixed
     */
    public function restore($id);

    /**
     * Get Order From Request
     * @param $id
     * @return mixed
     */
    public function getFromRequest($id);

}