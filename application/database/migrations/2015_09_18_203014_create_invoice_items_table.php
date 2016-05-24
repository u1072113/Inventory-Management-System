<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('description')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('shipDate')->nullable();
            $table->double('price', 15, 2)->nullable();//Price After Discount
            $table->double('discountPercentage')->nullable();
            $table->string('serialNumber')->nullable();
            $table->boolean('vatable')->nullable();

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
        Schema::drop('invoice_items');
    }
}
