<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('requesterName')->nullable();
            $table->string('requestNo')->nullable();
            $table->longText('emailAddresses')->nullable();
            $table->integer('owner')->nullable();
            $table->string('notifyOnLpoCreation')->nullable();
            $table->date('remindMeOn')->nullable();
            $table->string('remarks')->nullable();
            $table->dateTime('requestApprovedOn')->nullable();
            $table->dateTime('lpoCreatedOn')->nullable();
            $table->dateTime('lpoApprovedOn')->nullable();
            $table->boolean('requestApproved')->default(0);
            $table->boolean('lpoCreated')->default(0);
            $table->boolean('lpoApproved')->default(0);
            $table->integer('departmentId')->nullable();
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
        Schema::drop('purchase_requests');
    }
}
