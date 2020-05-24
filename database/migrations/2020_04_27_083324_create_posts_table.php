<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('cat_id');
            $table->unsignedBigInteger('photo_id')->nullable();
           
            $table->string('post_title');
            $table->longtext('post_description');
            $table->timestamps();

            $table->foreign('cat_id')->references('id')->on('categories');
            $table->foreign('photo_id')->references('id')->on('phototitles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}