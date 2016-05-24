<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePurchaseOrdersTable extends Migration
{
    /**purchase
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('plId');
            $table->string('poItemCode')->nullable();
            $table->string('poDescription')->nullable();
            $table->double('poQty', 15, 2);
            $table->double('poUnitPrice', 15, 2);
            $table->float('poDisc');
            $table->char('taxable', 1)->nullable();
            $table->double('poTotal', 15, 2)->nullable();
            $table->boolean('fullDelivery')->default(0);
            $table->boolean('partDelivery')->default(0);
            $table->float('delivered');
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
        Schema::drop('purchase_orders');
    }
}
