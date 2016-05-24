<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestocksTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restocks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("productID");
            $table->integer("departmentUse");
            $table->double("unitCost", 15, 2)->default(0);
            $table->double("itemCost", 15, 2)->default(0);
            $table->double("amount", 15, 2);
            $table->longText("invoice")->nullable();
            $table->longText("deliveryNote")->nullable();
            $table->integer("supplierID")->nullable();
            $table->boolean("isDamagedReturned")->default(0);
            $table->boolean("isMistakeDispatch")->default(0);
            $table->longText("remarks")->nullable();
            $table->longText("restockDocs");
            $table->integer("receivedBy");
            $table->longText('defectRemark');
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
        Schema::drop('restocks');
    }

}
