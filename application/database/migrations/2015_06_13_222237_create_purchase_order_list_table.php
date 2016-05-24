<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePurchaseOrderListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders_list', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('polSupplierId');
            $table->string('polSupplierName')->nullable();
            $table->date('polDateOfDelivery')->nullable();
            $table->string('polTermsOfPayment')->nullable();
            $table->date('polDeliverBy')->nullable();
            $table->boolean('fullDelivery')->default(0);
            $table->boolean('partDelivery')->default(0);
            $table->boolean('isFavourite')->default(0);
            $table->integer('departmentId')->nullable();
            $table->string('favouriteName')->nullable();
            $table->string('lpoNumber')->nullable();
            $table->string('internalRefNo')->nullable();
            $table->string('vatTaxAmount')->nullable();
            $table->string("company")->nullable();
            $table->string("lpoCurrencyType")->nullable();
            $table->string("prRequestNo")->nullable();
            $table->text("remarks")->nullable();
            $table->string('lpoStatus')->nullable();
            $table->date('lpoDate')->nullable();
            $table->text('rejectionReason')->nullable();
            $table->integer('companyId')->unsigned();
            $table->integer('updatedBy')->unsigned();
            $table->integer('createdBy')->unsigned();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('purchase_orders_list');
    }
}
