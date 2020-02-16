<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAdmin extends Migration
{
    /**
     * Run the migrations.
     * 范围粒度筛选
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_admin', function (Blueprint $table) {
            $table->increments('id')->comment('id');//唯一编号
            $table->string('name')->comment('范围');
            $table->string('user_id')->comment('绑定用户');
            $table->text('comment')->comment('备注');
            $table->boolean('status')->comment('有效')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('user_admin');
    }
}
