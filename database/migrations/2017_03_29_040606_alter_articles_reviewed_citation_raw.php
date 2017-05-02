<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterArticlesReviewedCitationRaw extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('citation_raw_reviewed')->nullable();
            $table->renameColumn('is_reviewed_citation', 'num_citation_reviewed');

        });
        Schema::table('articles', function (Blueprint $table) {
            $table->unsignedInteger('num_citation_reviewed')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
