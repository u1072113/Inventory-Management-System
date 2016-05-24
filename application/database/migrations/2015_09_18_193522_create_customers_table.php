<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('customerCode')->nullable();
            $table->string('customerName')->nullable();
            $table->string('contactPerson')->nullable();

            #Telephone
            $table->string('contactPersonPhone')->nullable();
            $table->string('companyPhone')->nullable();
            $table->string('contactPersonEmail')->nullable();
            $table->string('companyEmail')->nullable();

            #Contacts
            $table->string('street')->nullable();
            $table->string('zipCode')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('addressName1')->nullable();
            $table->string('addressName2');

            #Payments
            $table->double('creditLimit', 15, 2)->nullable();
            $table->double('discount', 15, 2)->nullable();

            #Account Status
            $table->boolean('active')->nullable();
            $table->string('disableReason')->nullable();

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
        Schema::drop('customers');
    }
}
