<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllBaseTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assertors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key_id')->unique();
            $table->timestampTz('date');
            $table->string('first_name')->nullable();
            $table->string('sur_name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password', 60)->nullable();

        });

        Schema::create('webpages', function (Blueprint $table) {

            $table->increments('id');
            $table->timestampTz('date');
            $table->string('title');
            $table->string('source');

        });


        Schema::create('assertions', function (Blueprint $table) {

            $table->increments('id');
            $table->timestampTz('date');
            $table->string('mode');

            $table->integer('asserted_by')->unsigned();
            $table->foreign('asserted_by')->references('id')->on('assertors');

            $table->integer('subject_id')->unsigned();
            $table->foreign('subject_id')->references('id')->on('webpages');

            $table->string('test_id')->nullable();
            $table->string('test_type')->nullable();

            $table->string('test_partof_id')->nullable();
            $table->string('test_partof_type')->nullable();

            $table->string('result_type')->nullable();
            $table->string('result_outcome')->nullable();

        });

        Schema::create('evaluations', function(Blueprint $table){
            $table->increments('id');
            $table->timestampTz('date');

            $table->integer('creator_id')->unsigned();
            $table->foreign('creator_id')->references('id')->on('assertors');

        });

        Schema::create('evaluation_assertion', function(Blueprint $table){
            $table->increments('id');

            $table->integer('evaluation_id')->unsigned();
            $table->foreign('evaluation_id')->references('id')->on('evaluations');

            $table->integer('assertion_id')->unsigned();
            $table->foreign('assertion_id')->references('id')->on('assertions');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('evaluation_assertion');
        Schema::drop('evaluations');
        Schema::drop('assertions');
        Schema::drop('webpages');
        Schema::drop('assertors');
    }

}
