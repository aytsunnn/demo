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
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table){
            $table->id();
            $table->string('name');
        });

        Schema::create('categories', function (Blueprint $table){
            $table->id();
            $table->string('name');
        });

        Schema::create('manufacturers', function (Blueprint $table){
            $table->id();
            $table->string('name');
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('article')->unique();
            $table->string('name');
            $table->float('price');
//            $table->bigInteger('supplier_id');
            $table->foreignId("supplier_id")->constrained();
            $table->foreignId('manufacturer_id')->constrained();
            $table->foreignId('category_id')->constrained();
            $table->integer('discount')->default(0);
            $table->unsignedInteger('quantity')->default(0);
            $table->string('description')->nullable();
            $table->string('image_path')->nullable();

//            $table->foreign('supplier_id')->references('id')->on('suppliers');
//            $table->foreign('manufacturer_id')->references('id')->on('manufacturers');
//            $table->foreign('category_id')->references('id')->on('categories');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('manufacturers');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('suppliers');

    }
};
