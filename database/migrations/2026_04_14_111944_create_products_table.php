<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('manufacturers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('article')->unique();
            $table->string('name');
            $table->double('price');
            $table->foreignId('seller_id')->constrained();
            $table->foreignId('manufacturer_id')->constrained();
            $table->foreignId('category_id')->constrained();
            $table->unsignedInteger('discount');
            $table->unsignedInteger('quantity');
            $table->string('description');
            $table->string('image_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sellers');
        Schema::dropIfExists('manufacturers');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('products');
    }
};
