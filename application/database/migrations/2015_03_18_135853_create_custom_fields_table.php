<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomFieldsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_fields', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('columnName');
            $table->string('table');
            $table->string('loop');
            $table->string('columnType');
            $table->string('fontawesome');
            $table->string('userView');
            $table->tinyInteger("renderAs");
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
        Schema::drop('custom_fields');
    }

}
