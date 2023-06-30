<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChowhubProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chowhub_products', function (Blueprint $table) {
            $table->id();
            $table->string('productName')->nullable();
            $table->longText('description')->nullable();
            $table->string('sku')->nullable();
            $table->string('pet_type')->nullable();

            $table->string('age')->nullable();
            $table->string('food_type')->nullable();
            $table->string('protein_type')->nullable();

            $table->set('type', ['Single Product', 'Variation'])->default('Single Product');
            $table->unsignedBigInteger('store_id')->nullable();

            $table->string('feature_image')->nullable();
            $table->float('real_price', 8, 2)->nullable();
            $table->float('sale_price', 8, 2)->nullable();
            $table->string('weight')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('status')->nullable();

            $table->timestamps();
        });

        Schema::create('chowhub_product_galleries', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('product_id');
            $table->string('image_path')->nullable();
            $table->string('priority')->nullable();

            $table->timestamps();
        });
        Schema::create('chowhub_product_description_images', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('product_id');
            $table->string('image_path')->nullable();
            $table->string('priority')->nullable();

            $table->timestamps();
        });
        Schema::create('chowhub_product_feature_page_images', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('product_id');
            $table->string('image_path')->nullable();
            $table->string('priority')->nullable();

            $table->timestamps();
        });
        Schema::create('chowhub_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('chowhub_product_tags', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('tag_id');
            $table->timestamps();
        });
        Schema::create('chowhub_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('chowhub_product_brands', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('brand_id');
            $table->timestamps();
        });
        Schema::create('chowhub_backend_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('chowhub_product_backend_tags', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('tag_id');
            $table->timestamps();
        });
        Schema::create('chowhub_variations_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->timestamps();
        });

		Schema::create('chowhub_variations_attributes_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attribute_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('name')->nullable();
            $table->timestamps();
        });

        Schema::create('chowhub_product_variations', function (Blueprint $table) {
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
        Schema::dropIfExists('chowhub_products');
        Schema::dropIfExists('chowhub_product_galleries');
        Schema::dropIfExists('chowhub_product_description_images');
        Schema::dropIfExists('chowhub_product_feature_page_images');
        Schema::dropIfExists('chowhub_backend_tags');
        Schema::dropIfExists('chowhub_product_backend_tags');
        Schema::dropIfExists('chowhub_tags');
        Schema::dropIfExists('chowhub_product_tags');
        Schema::dropIfExists('chowhub_brands');
        Schema::dropIfExists('chowhub_product_brands');
        Schema::dropIfExists('chowhub_variations_attributes');
        Schema::dropIfExists('chowhub_variations_attributes_values');
        Schema::dropIfExists('chowhub_product_variations');
    }
}
