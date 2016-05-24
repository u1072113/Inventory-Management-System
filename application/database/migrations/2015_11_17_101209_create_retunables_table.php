<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetunablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retunables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('itemId');
            $table->string('itemName');
            $table->integer('borrowerId');
            $table->string('borrowerName');
            $table->date('borrowDate');
            $table->date('dueDate');
            $table->date('returnDate');
            $table->longText('notes');
            $table->double('fineAmmount');
            $table->boolean('returned');
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
        Schema::drop('retunables');
    }
}
