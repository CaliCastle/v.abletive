<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('thumbnail');
            $table->text('description');
            $table->timestamp('updated_on');
            $table->timestamps();
        });

        DB::table('skills')->insert([
           ['name' => "live", "thumbnail" => "https://dn-abletive.qbox.me/v/images/skills/ableton.jpg"],
           ['name' => "launchpad", "thumbnail" => "https://dn-abletive.qbox.me/v/images/skills/launchpad.jpg"],
           ['name' => "produce", "thumbnail" => "https://dn-abletive.qbox.me/v/images/skills/produce.jpg"],
           ['name' => "dj", "thumbnail" => "https://dn-abletive.qbox.me/v/images/skills/dj.jpg"],
           ['name' => "controller", "thumbnail" => "https://dn-abletive.qbox.me/v/images/skills/controller.jpg"],
        ]);

        Schema::create('series_skill', function (Blueprint $table) {
            $table->unsignedInteger('series_id')->index();
            $table->unsignedInteger('skill_id')->index();

            $table->primary(['series_id', 'skill_id']);

            $table->foreign('series_id')->references('id')->on('series')->onDelete('cascade');
            $table->foreign('skill_id')->references('id')->on('skills')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('series_skill');
        Schema::drop('skills');
    }
}
