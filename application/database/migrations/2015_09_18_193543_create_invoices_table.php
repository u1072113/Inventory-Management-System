<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoiceNumber')->nullable();
            $table->string('receiptNumber')->nullable();
            $table->string('pickStatus')->nullable();
            $table->string('pickQuantity')->nullable();
            $table->string('deliveredQuantity')->nullable();
            $table->string('currency')->nullable();
            $table->boolean('paid')->nullable();
            $table->integer('companyId')->unsigned();
            $table->integer('updatedBy')->unsigned();
            $table->integer('createdBy')->unsigned();
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
        Schema::drop('invoices');
    }
}
