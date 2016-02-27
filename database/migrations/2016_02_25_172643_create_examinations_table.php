<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExaminationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examinations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
        });

        Schema::create('examination_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('examination_id');
            $table->text('title');
            $table->timestamps();
        });

        Schema::create('examination_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('question_id');
            $table->text('title');
            $table->boolean('correct');
            $table->timestamps();

            $table->foreign('question_id')->references('id')->on('examination_questions')->onDelete('cascade');
        });

        Schema::create('certificates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('examination_id');
            $table->string('image');
            $table->timestamps();
        });

        Schema::create('examination_user', function (Blueprint $table) {
            $table->unsignedInteger('examination_id')->index();
            $table->unsignedInteger('user_id')->index();
            $table->timestamps();

            $table->primary(['examination_id', 'user_id']);
            $table->foreign('examination_id')->references("id")->on('examinations')->onDelete('cascade');
            $table->foreign('user_id')->references("id")->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('examination_user');
        Schema::drop('certificates');
        Schema::drop('examination_answers');
        Schema::drop('examination_questions');
        Schema::drop('examinations');
    }
}
