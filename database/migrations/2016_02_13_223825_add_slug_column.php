<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlugColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('series', function (Blueprint $table) {
            $table->string('slug')->index()->unique();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('slug')->index()->unique()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('series', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
