<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function(Blueprint $table) {
            $table->integer('lightspeed_category_id')->nullable();
        });

        Schema::table('brands', function(Blueprint $table) {
            $table->integer('lightspeed_brand_id')->nullable();
        });

        Schema::table('stores', function(Blueprint $table) {
            $table->string('lightspeed_vendor_id')->nullable();
        });
        Schema::table('products', function(Blueprint $table) {
            $table->string('matrix_id')->nullable();
        });
        Schema::table('product_variations', function(Blueprint $table) {
            $table->string('lightspeed_item_id')->nullable();
        });
        Schema::table('orders', function(Blueprint $table) {
            $table->string('lightspeedOrderId')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('lightspeed_category_id');
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('lightspeed_brand_id');
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('lightspeed_vendor_id');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('matrix_id');
        });
        Schema::table('product_variations', function (Blueprint $table) {
            $table->dropColumn('lightspeed_item_id');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('lightspeedOrderId');
        });
    }
}
