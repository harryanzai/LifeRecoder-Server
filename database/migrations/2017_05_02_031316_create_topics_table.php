<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('话题的名称');
            $table->string('bio')->nullable()->comment('话题的简介');


            // 多态一对多关联
            $table->integer('topicable')->nullable()->comment('关联的id');
            $table->string('tipicable_type')->nullable()->comment('关联的类型');

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
        Schema::dropIfExists('topics');
    }
}
