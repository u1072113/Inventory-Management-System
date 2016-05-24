<?php
namespace Inventory\Repository\PurchaseRequest;


interface PurchaseRequestInterface
{

    /**
     * Add a purchase Order
     * @param $purchaseRequest
     * @return mixed
     * @internal param $purchaseOrder
     * @internal param $supplierId
     * @internal param array $order
     * @internal param int $isFavourite
     * @internal param int $isEmail
     */
    public function addPurchaseRequest($purchaseRequest);

    /**
     * Update Existing Purchase Order
     * @param $purchaseRequestId
     * @param $purchaseRequest
     * @return
     * @internal param $purchaseId
     * @internal param $purchaseOrder
     */
    public function updatePurchaseOrder($purchaseRequestId, $purchaseRequest);

    /**
     * Generate PDF
     * @param $purchaseRequestId
     * @return
     * @internal param $purchaseOrderId
     */
    public function generatePdf($purchaseRequestId);

    /**
     * Send Email with Purchase Order
     * @param $supplierId
     * @param $orderId
     */
    public function sendEmail($supplierId, $orderId);


    /**
     * Get list of purchase orders
     * @param array $params
     * @return mixed
     */
    public function getPurchaseRequests(array $params);

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
    public function getPurchaseRequest($purchaseOrderId);


    /**
     * Deletes Purchase Order
     * @param $purchaseOrderId
     */
    public function deletePurchaseRequest($purchaseOrderId);

    /**
     * Get number of purchase orders
     * @return int
     */
    public function getPurchaseRequestCount();

    /**
     * Suggest Purchase Order Items
     * @return mixed
     */
    public function suggestPurchaseRequest();

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
    public function getProductForPurchaseRequest($purchaseOrderId);

    /**
     * Restore a deleted item
     * @return mixed
     */
    public function restore($id);
    
}
