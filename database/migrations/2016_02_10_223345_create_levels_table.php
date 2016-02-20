<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('levels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('experience');
            $table->timestamps();
        });

        DB::table('levels')->insert([
            ['name' => 'beginner', 'experience' => 0],
            ['name' => 'starting', 'experience' => 500],
            ['name' => 'intermediate', 'experience' => 1500],
            ['name' => 'skilled', 'experience' => 5000],
            ['name' => 'maestro', 'experience' => 15000],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('levels');
    }
}
