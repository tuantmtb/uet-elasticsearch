<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoriesOrganizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histories_organization', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('org_from')->unsigned()->nullable()->index();
            $table->foreign('org_from')->references('id')->on('organizes')->onDelete('set null');

            $table->integer('org_to')->unsigned()->nullable()->index();
            $table->foreign('org_to')->references('id')->on('organizes')->onDelete('set null');

            $table->mediumText('action')->nullable();

            $table->text('description')->nullable();

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
        Schema::dropIfExists('histories_organization');
    }
}
