<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('companyName');
            $table->string('city');
            $table->string('country');
            $table->string('defaultCurrency');
            $table->string('companySlogan');
            $table->string('street');
            $table->string('zipCode');
            $table->string('phone');
            $table->string('defaultLpoTaxAmmount');
            $table->string("language")->default("en");
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
        Schema::drop('companies');
    }
}
