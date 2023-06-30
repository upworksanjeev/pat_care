<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLitterHubCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('litterhub_carts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default('0');
            $table->text('key');
            $table->timestamps();
        });
        Schema::create('litterhub_cart_items', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('cart_id');
            $table->integer('quantity')->default(0);
            $table->integer('variation_product_id')->default(0);
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
        Schema::dropIfExists('litterhub_carts');
        Schema::dropIfExists('litterhub_cart_items');

    }
}
