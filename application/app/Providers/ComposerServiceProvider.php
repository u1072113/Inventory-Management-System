<?php namespace App\Providers;


use App\Http\ViewComposers\ProductsComposer;
use View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $settings = array(
            'layouts.master',
            'printers.printerdetails',
            'dashboard.*',
            'settings.*'
        );

        $pagination = array(
            'products.tableview'
        );

        View::composer('layouts.sidebar', 'App\Http\ViewComposers\SidebarComposer');
        View::composer('dashboard.*', 'App\Http\ViewComposers\SidebarComposer');
        View::composer('dispatches.form', 'App\Http\ViewComposers\DispatchesComposer'); // Why isnt this working
        View::composer('products.tableview', 'App\Http\ViewComposers\ProductsComposer');
        View::composer('restocks.form', 'App\Http\ViewComposers\RestocksComposer');
        View::composer('users.createupdateuser', 'App\Http\ViewComposers\UserFormComposer');
        View::composer('staff.createupdateuser', 'App\Http\ViewComposers\StaffFormComposer');
        View::composer('*', 'App\Http\ViewComposers\SortComposer');
        View::composer('layouts.partials.email', 'App\Http\ViewComposers\EmailsComposer');
        View::composer($settings, 'App\Http\ViewComposers\SettingsComposer');

        View::composer('purchaseorder.newpurchaseorder', 'App\Http\ViewComposers\PurchaseOrderComposer');

        View::composer('invoice.newinvoice', 'App\Http\ViewComposers\PurchaseOrderComposer');
        View::composer('purchaseorder.editpurchaseorder', 'App\Http\ViewComposers\PurchaseOrderComposer');
        View::composer('purchaseorder.index', 'App\Http\ViewComposers\PurchaseOrderComposer');
        View::composer('customer.form', 'App\Http\ViewComposers\CustomerComposer');
        View::composer('purchaserequest.newpurchaserequest', 'App\Http\ViewComposers\PurchaseRequestComposer');
        View::composer('purchaserequest.index', 'App\Http\ViewComposers\PurchaseRequestComposer');
        /*
        View::composer('products.index', 'App\Http\ViewComposers\ProductsComposer');
        View::composer('products.newproduct', 'App\Http\ViewComposers\ProductsComposer');
        View::composer('products.import', 'App\Http\ViewComposers\ProductsComposer');
        //Restocks
        View::composer('restocks.index', 'App\Http\ViewComposers\RestocksComposer');
        View::composer('restocks.restock', 'App\Http\ViewComposers\RestocksComposer');
        //Suppliers
        View::composer('suppliers.index', 'App\Http\ViewComposers\SuppliersComposer');
        View::composer('suppliers.newsupplier', 'App\Http\ViewComposers\SuppliersComposer');
        View::composer('dispatches.index', 'App\Http\ViewComposers\DispatchesComposer');
        View::composer('dispatches.dispatch', 'App\Http\ViewComposers\DispatchesComposer');
        View::composer('users.index', 'App\Http\ViewComposers\UserFormComposer');
        View::composer('users.userroles', 'App\Http\ViewComposers\UserFormComposer');
        View::composer('users.import', 'App\Http\ViewComposers\UserFormComposer');
        View::composer('users.createupdateuser', 'App\Http\ViewComposers\UserFormComposer');
        */
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}
