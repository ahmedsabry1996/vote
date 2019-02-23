<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotersTable extends Migration
{

    public function up()
    {
        Schema::create('voters', function (Blueprint $table) {
            $table->increments('id');
            $table->ipAddress('voter_ip');
            $table->integer('vote_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('voters');
    }
}
