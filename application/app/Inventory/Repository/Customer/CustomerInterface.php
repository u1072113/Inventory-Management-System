<?php


namespace Inventory\Repository\Customer;


interface CustomerInterface
{
    /**
     * Create Customer
     * @param $customer
     * @return mixed
     */
    public function createCustomer($customer);

    /**
     * Get Customer from Database
     * @param $id
     * @return mixed
     */
    public function getCustomer($id);

    /**
     * Update Customer
     * @param $customerId
     * @param $customer
     * @return mixed
     */
    public function updateCustomer($customerId, $customer);

    /**
     * Delete a Customer
     * @param $customerId
     * @return mixed
     */
    public function deleteCustomer($customerId);

    /**
     * Get Customers with Pagination
     * @return mixed
     */
    public function getCustomers(array $params);

    /**
     * Get Customers For report without Pagination
     * @return mixed
     */
    public function getCustomersForReport();

    /**
     * Get Count of Customers in database
     * @return mixed
     */
    public function getCustomerCount();


    /***
     * Restores a deleted Customer
     * @param $id
     * @return mixed
     */
    public function restoreDeletedCustomer($id);
}
