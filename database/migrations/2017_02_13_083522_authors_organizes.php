<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AuthorsOrganizes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authors_organizes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('author_id')->unsigned()->index(); // article
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');

            $table->integer('organize_id')->unsigned()->nullable()->index(); // article
            $table->foreign('organize_id')->references('id')->on('organizes')->onDelete('set null');

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
        Schema::dropIfExists('authors_organizes');
    }
}
