<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles_relations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cite_id')->unsigned()->index(); // article
            $table->foreign('cite_id')->references('id')->on('articles')->onDelete('cascade');
            $table->integer('cited_id')->unsigned()->index(); // article cite_id use cited_id // cited là bài được chỉ ra trong trích dẫn của bài cite_id
            $table->foreign('cited_id')->references('id')->on('articles')->onDelete('cascade');
//            $table->primary(['cite_id', 'cited_id']);
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
        Schema::dropIfExists('articles_relations');
    }
}
