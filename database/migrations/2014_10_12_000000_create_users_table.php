<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->unique();
            $table->string('name')->unique();
            $table->string('display_name')->index();
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->date('expired_at');
            $table->unsignedBigInteger('experience')->default(0);
            $table->enum('role', ["Member", "Subscriber", "Tutor", "Admin"]);
            $table->string('avatar')->default("https://dn-abletive.qbox.me/images/512px.png");
            $table->timestamp('registered_at')->default("0000-00-00 00:00:00");
            $table->string('description');
            $table->rememberToken();
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
        Schema::drop('users');
    }
}
