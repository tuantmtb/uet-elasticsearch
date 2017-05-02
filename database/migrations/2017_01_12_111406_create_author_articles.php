<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorArticles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles_authors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('author_id')->unsigned()->index(); // article
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');

            $table->integer('article_id')->unsigned()->index(); // article
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
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
        Schema::dropIfExists('articles_authors');
    }
}
