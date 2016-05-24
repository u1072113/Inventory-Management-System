<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMessagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userId');
            $table->integer('itemId')->nullable();
            $table->string('messageFrom')->nullable();
            $table->text('text')->nullable();
            $table->text('attachments')->nullable();
            $table->boolean('messageType')->default(0);
            $table->boolean('sent')->default(0);
            $table->boolean('isRead')->default(0);
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
        Schema::drop('messages');
    }

}
