<?php namespace Inventory\Repository\Invoice;

use App\Product;
use DB;

class InvoiceEntity implements InvoiceInterface
{

    /**
     * Counts Number of Invoices
     * @return int
     */
    public function count()
    {
        // TODO: Implement count() method.
    }

    /**
     * Gets Invoices adds Navigation Parameters
     * @return mixed
     */
    public function getInvoices()
    {
        // TODO: Implement getInvoices() method.
    }

    /**
     * Get Invoices for Report without Pagination
     * @return mixed
     */
    public function getInvoicesForReport()
    {
        // TODO: Implement getInvoicesForReport() method.
    }

    /**
     * Generates a unique Invoice Number
     * @return mixed
     */
    public function generateInvoiceNumber()
    {
        // TODO: Implement generateInvoiceNumber() method.
    }

    /**
     * Creates Invoice and Related Items
     * @param $invoice
     * @return mixed
     */
    public function createInvoice($invoice)
    {
        // TODO: Implement createInvoice() method.
    }

    /**
     * @param $invoiceId
     * @param array $invoiceItems
     * @return mixed
     */
    public function createInvoiceItems($invoiceId, array $invoiceItems)
    {
        // TODO: Implement createInvoiceItems() method.
    }

    /**
     * Updates an existing Invoice
     * @param $invoiceId
     * @param $invoice
     * @return mixed
     */
    public function updateInvoice($invoiceId, $invoice)
    {
        // TODO: Implement updateInvoice() method.
    }

    /**
     * Deletes an existing Invoice
     * @param $invoiceId
     * @return mixed
     */
    public function deleteInvoice($invoiceId)
    {
        // TODO: Implement deleteInvoice() method.
    }

    /**
     * Emails an invoice to supplied Emails
     * @param $invoiceId
     * @param array $emails
     * @return mixed
     */
    public function mailInvoice($invoiceId, array $emails)
    {
        // TODO: Implement mailInvoice() method.
    }

    /**
     * Get Invoice to Display to user.
     * @param $invoiceId
     * @return mixed
     */
    public function getInvoice($invoiceId)
    {
        // TODO: Implement getInvoice() method.
    }

    /**
     * Get one product with restock suggestion
     * @param $purchaseOrderId
     * @return mixed
     */
    public function getProductForInvoice($productId)
    {
        return Product::select(
            DB::raw(
                'productName,
                 1 as quantity,
                 0 as discountPercentage,
                 IFNULL(abs(reorderAmount - amount),1) as reorder,
                 IFNULL((case when unitCost < 1 Then 1 end),1) as price,
                  id
                  '
            )
        )
            ->whereId($productId)
            ->first();
    }
}