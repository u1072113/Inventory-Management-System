<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequisitionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisitions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reqDescription');
            $table->string('reqPurpose');
            $table->string('reqQty');
            $table->integer('reqDepartment');
            $table->json('reqNotify');
            $table->boolean('reqApproved');
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
        Schema::drop('requisitions');
    }
}
