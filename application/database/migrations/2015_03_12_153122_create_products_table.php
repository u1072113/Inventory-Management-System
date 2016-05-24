<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('productName');
            $table->string('location');
            $table->string('productSerial')->nullable();
            $table->decimal('amount')->nullable();
            $table->decimal("unitCost")->nullable();
            $table->decimal('reorderAmount')->nullable();
            $table->integer('maximumOrderAmount')->nullable();
            $table->date('expirationDate')->nullable();
            $table->longText('barcode')->nullable();
            $table->longText('qrcode')->nullable();
            $table->longText('productImage')->nullable();
            $table->boolean('canAutoOrder')->nullable();
            $table->string('autoOrderEmail')->nullable();
            $table->longText('emailFormat')->nullable();
            $table->string('barcodeFileName')->nullable();
            $table->string('qrcodeFileName')->nullable();
            //Returnables Format
            $table->string('categoryName')->nullable();
            $table->integer('categoryId')->nullable();
            $table->text('productSpecification');
            $table->double('buyingPrice', 15, 2);
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
        Schema::drop('products');
    }

}
