<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * 用户发布的图片集
     * @return void
     */
    public function up()
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('title')->nullable()->comment('图片集的标题');
            $table->string('content')->nullable()->comment('图片集的描述');
            // 心情
            $table->integer('mood_id')->nullable()->comment('所对应的心情');
            // 标签

            // 私密的只能自己查看
            $table->boolean('secret')->default(false);

            // 默认为草稿状态
            $table->boolean('publish')->default(false);
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
        Schema::dropIfExists('galleries');
    }
}
