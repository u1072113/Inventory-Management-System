<?php


namespace Inventory\Repository\Invoice;


interface InvoiceInterface
{
    /**
     * Counts Number of Invoices
     * @return int
     */
    public function count();

    /**
     * Gets Invoices adds Navigation Parameters
     * @return mixed
     */
    public function getInvoices();

    /**
     * Get Invoices for Report without Pagination
     * @return mixed
     */
    public function getInvoicesForReport();


    /**
     * Generates a unique Invoice Number
     * @return mixed
     */
    public function generateInvoiceNumber();

    /**
     * Creates Invoice and Related Items
     * @param $invoice
     * @return mixed
     */
    public function createInvoice($invoice);

    /**
     * @param $invoiceId
     * @param array $invoiceItems
     * @return mixed
     */
    public function createInvoiceItems($invoiceId, array $invoiceItems);

    /**
     * Updates an existing Invoice
     * @param $invoiceId
     * @param $invoice
     * @return mixed
     */
    public function updateInvoice($invoiceId, $invoice);

    /**
     * Deletes an existing Invoice
     * @param $invoiceId
     * @return mixed
     */
    public function deleteInvoice($invoiceId);

    /**
     * Emails an invoice to supplied Emails
     * @param $invoiceId
     * @param array $emails
     * @return mixed
     */
    public function mailInvoice($invoiceId, array $emails);

    /**
     * Get Invoice to Display to user.
     * @param $invoiceId
     * @return mixed
     */
    public function getInvoice($invoiceId);


    public function getProductForInvoice($productId);
}