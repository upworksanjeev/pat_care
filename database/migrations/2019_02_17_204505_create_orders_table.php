<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transaction_id')->nullable();
            $table->string('shipping_id')->default(0);
            $table->unsignedInteger('user_id')->default(0);
            $table->enum('status', ['pending','processing','completed','decline'])->default('pending');
            $table->float('grand_total');
            $table->integer('item_count');
            $table->string('payment_method')->default('cash_on_delivery');
            $table->string('shippingmethod')->nullable();
            $table->text('remark')->nullable();

            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('variation_id')->default(0);
            $table->float('unit_price')->nullable();;
            $table->integer('quantity')->nullable();;
            $table->float('total_price')->nullable();;

            $table->timestamps();
        });

        Schema::create('shippings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id')->default(0);
            $table->string('sh_name');
            $table->string('sh_address');
            $table->string('sh_city');
            $table->string('sh_state');
            $table->string('sh_country');
            $table->string('sh_zip_code');
            $table->string('sh_phone');
            $table->string('sh_email');
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
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('shippings');
    }
}
