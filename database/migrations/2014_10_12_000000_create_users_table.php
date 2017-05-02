<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nickname',30)->default('佚名')->comment('用户昵称');
            $table->string('email')->unique()->nullable()->comment('邮箱');
            $table->string('mobile')->unique()->comment('手机号');
            $table->string('password')->comment('用户的密码');
            $table->enum('gender',['男','女','未设置'])->default('未设置')->comment('性别');
            $table->string('avatar', 100)->nullable()->comment('头像');
            $table->timestamp('last_login_time')->nullable()->comment('最后一次登录时间');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
