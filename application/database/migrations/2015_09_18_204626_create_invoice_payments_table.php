<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('invoiceId')->nullable();
            $table->boolean('downPayment')->nullable();
            $table->double('paymentAmount', 15, 2)->nullable();
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
        Schema::drop('invoice_payments');
    }
}
