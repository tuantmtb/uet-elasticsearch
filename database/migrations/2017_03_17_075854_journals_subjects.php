<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class JournalsSubjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journals_subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('journal_id')->index();
            $table->foreign('journal_id')->references('id')->on('journals')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('subject_id')->index();
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('journals_subjects');
    }
}
