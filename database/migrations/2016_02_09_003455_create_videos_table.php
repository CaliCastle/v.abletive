<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('series_id');
            $table->string('title');
            $table->string('duration', 10);
            $table->unsignedInteger('experience')->default(100);
            $table->unsignedInteger('user_id');
            $table->string('description');
            $table->dateTime('published_at')->default("0000-00-00 00:00:00");
            $table->timestamps();

            $table->foreign('series_id')->references('id')->on('series')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('videos');
    }
}
