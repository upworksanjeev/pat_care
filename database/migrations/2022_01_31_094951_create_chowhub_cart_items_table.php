<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChowhubCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chowhub_cart_items', function (Blueprint $table) {
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
        Schema::dropIfExists('chowhub_cart_items');
    }
}
