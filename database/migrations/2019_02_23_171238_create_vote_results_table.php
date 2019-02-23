<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoteResultsTable extends Migration
{

    public function up()
    {
        Schema::create('vote_results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vote_id');
            $table->integer('yes')->default(0);
            $table->integer('no')->default(0);
            $table->integer('abstain')->default(0)->nullabel();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('vote_results');
    }
}
