<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDispatchesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispatches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("dispatchedItem");
            $table->integer("dispatchedTo");
            $table->integer("departmentId")->nullable();
            $table->integer("categoryId")->nullable();
            $table->string("categoryName")->nullable();
            $table->integer("userId");
            $table->double("amount", 15, 2);
            $table->longText("remarks")->nullable();
            $table->boolean("isReturned")->default(0);
            $table->boolean('isMistakeDispatch')->default(0);
            $table->longText('defectRemark');
            $table->double("totalCost", 15, 2)->nullable();
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
        Schema::drop('dispatches');
    }

}
