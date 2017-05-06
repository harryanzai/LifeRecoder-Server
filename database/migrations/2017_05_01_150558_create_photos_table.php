<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * 用户发送的图片
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('gallery_id')
                ->unsigned()->comment('属于的图片集');
//            $table->foreign('gallery_id')->references('id')
//                ->on('galleries')
//                ->ondelete('cascade');
            $table->string('name')->comment('图片的名称');
            $table->string('path')->comment('图片的路径');
            $table->string('thumbnail_path')->comment('压缩图片的路径');
            $table->string('description')->nullable()->comment('压缩图片的路径');

            $table->timestamps();


        });

//        Schema::table('photos', function (Blueprint $table) {
//
//
//
//        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('photos');
    }
}
