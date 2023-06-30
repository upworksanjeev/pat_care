<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->integer('user_id');
            $table->float('rating')->default(0);
            $table->string('title');

            $table->longText('description');
            $table->boolean('status')->default(0);
            $table->boolean('verified_buyer')->default(0);
            $table->timestamps();
        });
        Schema::create('rating_galleries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rating_id');
            $table->string('image_path');
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
        Schema::dropIfExists('ratings');
        Schema::dropIfExists('rating_galleries');

    }
}
