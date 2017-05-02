<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersArticlesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_articles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned(); // article
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('article_id')->unsigned(); // article
            $table->foreign('article_id')->references('id')->on('articles');
            // $table->primary(['user_id', 'article_id']);
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
        Schema::dropIfExists('users_articles');
    }
}
