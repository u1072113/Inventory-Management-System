<?php namespace Inventory\Repository\Customer;

use Inventory\Fields\CustomFieldsInterface;
use App\Customer;
use Schema;

class CustomerEntity implements CustomerInterface
{
    /**
     * Create Customer
     * @param $customer
     * @return mixed
     */
    public function createCustomer($customer)
    {
        return Customer::create($customer);
    }

    /**
     * Get Customer from Database
     * @param $id
     * @return mixed
     */
    public function getCustomer($id)
    {
        return Customer::findOrFail($id);
    }

    /**
     * Update Customer
     * @param $customerId
     * @param $customer
     * @return mixed
     */
    public function updateCustomer($customerId, $customer)
    {
        return Customer::find($customerId)->update($customer);
    }

    /**
     * Delete a Customer
     * @param $customerId
     * @return mixed
     */
    public function deleteCustomer($customerId)
    {
        return Customer::destroy($customerId);
    }

    /**
     * Get Customers with Pagination
     * @return mixed
     */
    public function getCustomers(array $params)
    {
        if ($params['sort']['sortBy'] and $params['sort']['direction']) {
            return Customer::orderBy(
                $params['sort']['sortBy'],
                $params['sort']['direction']
            )->paginate(
                env('RECORDS_VIEW')
            )->setPath('');
        }
        $customer = new Customer();
        if (isset($params['deleted']['deleted'])) {
            $customer = $customer->onlyTrashed();
        }
        //Search
        if ($params['search']['search']) {
            $customer = $this->search($customer, $params['search']['search'], 'customers');
        }

        return $customer->orderBy('created_at', 'DESC')->paginate(env('RECORDS_VIEW'))->setPath('');
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
        $first = $columns[1];
        $item = $item->where($first, 'LIKE', "%{$search}%");
        foreach ($columns as $column) {
            $item = $item->orWhere($column, 'LIKE', "%{$search}%");
        }
        return $item;
    }

    /**
     * Get Customers For report without Pagination
     * @return mixed
     */
    public function getCustomersForReport()
    {
        // TODO: Implement getCustomersForReport() method.
    }

    /**
     * Get Count of Customers in database
     * @return mixed
     */
    public function getCustomerCount()
    {
        return Customer::count();
    }

    /***
     * Restores a deleted Customer
     * @param $id
     * @return mixed
     */
    public function restoreDeletedCustomer($id)
    {
        return Customer::withTrashed()->find($id)->restore();
    }


}