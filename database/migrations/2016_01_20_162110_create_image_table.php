<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bucket_id')->unsigned();
            $table->foreign('bucket_id')->references('id')->on('bucket')->onDelete('cascade');
            $table->string('description');
            $table->string('filename');
            $table->integer('size')->unsigned();
            $table->string('mime');
            $table->string('md5',32);
            $table->boolean('deleted');
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
        Schema::drop('image');
    }
}
