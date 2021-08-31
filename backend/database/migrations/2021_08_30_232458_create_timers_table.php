<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('memo')->nullable();
            $table->unsignedBigInteger('user_id'); //←見直し
            $table->timestamp('started_at');
            $table->timestamp('stopped_at')->default(null)->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('category_id')->nullable(); //←見直し
            $table->string('category_name')->nullable();
            $table->string('category_color')->nullable();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timers');
    }
}