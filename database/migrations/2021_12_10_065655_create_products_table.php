<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('productName')->nullable();
            $table->longText('description')->nullable();
            $table->string('sku')->nullable();
            $table->string('banner_image')->nullable();
            $table->longText('about_description')->nullable();


            $table->set('type', ['Single Product', 'Variation'])->default('Single Product');
            $table->unsignedBigInteger('store_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();

            $table->string('feature_image')->nullable();
            $table->float('real_price', 8, 2)->nullable();
            $table->float('sale_price', 8, 2)->nullable();
            $table->integer('weight')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('status')->nullable();
            $table->string('seo_title')->nullable();
            $table->longText('meta_description')->nullable();


            $table->timestamps();
        });

        Schema::create('product_galleries', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('product_id');
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
        Schema::create('product_description_details', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('product_id');
            $table->string('image_path')->nullable();
            $table->longText('value')->nullable();
            $table->timestamps();
        });
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('product_tags', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('tag_id');
            $table->timestamps();
        });
        Schema::create('variations_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->timestamps();
        });

		Schema::create('variations_attributes_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attribute_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('name')->nullable();
            $table->timestamps();
        });

        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->float('real_price', 8, 2)->nullable();
            $table->float('sale_price', 8, 2)->nullable();
            $table->string('image')->nullable();
            $table->string('weight')->nullable();
            $table->string('quantity')->nullable();
            $table->json('variation_attributes_name_id')->nullable();
            $table->string('sku')->nullable();
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
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_galleries');
        Schema::dropIfExists('variations_attributes');
        Schema::dropIfExists('variations_attributes_values');
        Schema::dropIfExists('product_variations');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('product_tags');
    }
}
