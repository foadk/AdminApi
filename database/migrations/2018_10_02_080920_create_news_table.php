<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('news_cat_id');
            $table->string('title');
            $table->string('description')->nullable();
            $table->text('content')->nullable();
            $table->integer('position')->nullable();
            $table->boolean('display')->nullable();
            $table->timestamps();

            $table->foreign('news_cat_id')->references('id')->on('news_cats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
}
