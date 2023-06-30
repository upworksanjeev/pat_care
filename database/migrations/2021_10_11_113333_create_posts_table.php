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
            $table->increments('id');
            $table->unsignedBigInteger('created_by');
            $table->text('title');
            $table->text('slug');
            $table->longText('content');
            $table->longText('css');

            $table->text('category');
            $table->text('status');
            $table->text('feature_image');

            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users');  
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
