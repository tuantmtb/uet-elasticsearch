<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title')->nullable();
            $table->text('abstract')->nullable(); //new
            $table->text('author')->nullable();
            $table->string('volume')->nullable();
            $table->string('number')->nullable();
            $table->string('year')->nullable();
            $table->text('uri')->nullable();
            $table->text('source')->nullable(); //new
            $table->text('usable')->nullable(); //new
            $table->text('reference')->nullable(); //new
            $table->text('titleOnGoogle')->nullable();
            $table->string('cluster_id')->nullable(); // id journal if have 2 source above = gId
            $table->string('cites_id')->nullable();  // id relation citation scholar
            $table->integer('cites_count')->nullable(); // count
            $table->text('mla')->nullable();
            $table->text('apa')->nullable();
            $table->text('chicago')->nullable();
            $table->text('harvard')->nullable();
            $table->text('vancouver')->nullable();
            $table->boolean('is_reviewed')->nullable();

            $table->integer('journal_id')->unsigned()->nullable()->index();
            $table->foreign('journal_id')->references('id')->on('journals')->onDelete('set null');

            $table->integer('editor_id')->unsigned()->nullable()->index();
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('set null');

            $table->string('language')->nullable();

            $table->text('keyword')->nullable();

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
        Schema::dropIfExists('articles');
    }

}
