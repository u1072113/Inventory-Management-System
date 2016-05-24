<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class InventoryServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind('Inventory\Repository\Department\DepartmentInterface', 'Inventory\Repository\Department\NewDepartment');
        $this->app->bind('Inventory\Printers\PrinterInterface', 'Inventory\Printers\PrinterEntity');
        $this->app->bind('Inventory\Repository\Dispatch\DispatchInterface', 'Inventory\Repository\Dispatch\DispatchItem');
        $this->app->bind('Inventory\Repository\Product\ProductInterface', 'Inventory\Repository\Product\ProductEntity');
        $this->app->bind('Inventory\Repository\Restock\RestockInterface', 'Inventory\Repository\Restock\RestockItem');
        $this->app->bind('Inventory\Repository\Supplier\SupplierInterface', 'Inventory\Repository\Supplier\NewSupplier');
        $this->app->bind('Inventory\Repository\User\UserInterface', 'Inventory\Repository\User\StockUsers');
        $this->app->bind('Inventory\Fields\CustomFieldsInterface', 'Inventory\Fields\CustomFields');
        $this->app->bind('Inventory\Repository\Model\ModelInterface', 'Inventory\Repository\Model\NewModel');
        $this->app->bind('Inventory\Ldap\LdapInterface', 'Inventory\Ldap\Users');
        $this->app->bind('Inventory\Repository\UserRoles\UserRoleInterface', 'Inventory\Repository\UserRoles\UserRolesEntity');
        $this->app->bind('Inventory\Repository\Setting\SettingsInterface', 'Inventory\Repository\Setting\SettingEntity');
        $this->app->bind('Inventory\Repository\Message\MessageInterface', 'Inventory\Repository\Message\MessageEntity');
        $this->app->bind('Inventory\Repository\Staff\StaffInterface', 'Inventory\Repository\Staff\StaffEntity');
        $this->app->bind('Inventory\Repository\Customer\CustomerInterface', 'Inventory\Repository\Customer\CustomerEntity');
        $this->app->bind('Inventory\Repository\Invoice\InvoiceInterface', 'Inventory\Repository\Invoice\InvoiceEntity');

        $this->app->bind('Inventory\Repository\PurchaseOrder\PurchaseOrderInterface', 'Inventory\Repository\PurchaseOrder\PurchaseOrderEntity');
        $this->app->bind('Inventory\Repository\PurchaseRequest\PurchaseRequestInterface', 'Inventory\Repository\PurchaseRequest\PurchaseRequestEntity');
    }

}
