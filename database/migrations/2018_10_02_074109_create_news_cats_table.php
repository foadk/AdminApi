<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsCatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_cats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('news_cat_id')->nullable();
            $table->string('title');
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
        Schema::dropIfExists('news_cats');
    }
}
