<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolutionHubProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solutionhub_products', function (Blueprint $table) {
            $table->id();
            $table->string('productName')->nullable();
            $table->longText('description')->nullable();
            $table->string('tag')->nullable();
            $table->string('status')->nullable();
            $table->string('feature_image')->nullable();
            $table->integer('separation_anxiety')->nullable();
            $table->integer('aggressive_chewers')->nullable();

            $table->integer('teething')->nullable();
            $table->integer('boredom')->nullable();
            $table->integer('disabled')->nullable();
            $table->integer('energetic')->nullable();
            $table->timestamps();
        });
        Schema::create('solutionhub_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('solutionhub_product_brands', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('brand_id');
            $table->timestamps();
        });
        Schema::create('solutionhub_product_categories', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();
        });
        Schema::create('solutionhub_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('solutionhub_product_tags', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('tag_id');
            $table->timestamps();
        });
        Schema::create('solutionhub_backend_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('solutionhub_product_backend_tags', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('tag_id');
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
        Schema::dropIfExists('solutionhub_products');
        Schema::dropIfExists('solutionhub_backend_tags');
        Schema::dropIfExists('solutionhub_product_backend_tags');
        Schema::dropIfExists('solutionhub_tags');
        Schema::dropIfExists('solutionhub_product_tags');
        Schema::dropIfExists('solutionhub_brands');
        Schema::dropIfExists('solutionhub_product_brands');
        Schema::dropIfExists('solutionhub_product_categories');

    }
}
