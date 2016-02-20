<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSeriesVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('series_video', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('series_id')->index();
            $table->unsignedInteger('video_id')->index();

            $table->foreign('series_id')->references('id')->on('series')->onDelete('cascade');
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');

//            $table->timestamps();
            $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('series_video');
    }
}
