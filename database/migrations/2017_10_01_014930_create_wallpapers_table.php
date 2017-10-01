<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWallpapersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallpapers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url', 255);
            $table->string('title', 255);
            $table->string('content');
            $table->string('location', 255);
            $table->integer('view')->default(0);
            $table->integer('heart')->default(0);
            $table->integer('download')->default(0);
            $table->string('calendar', 255);
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
        Schema::dropIfExists('wallpapers');
    }
}
