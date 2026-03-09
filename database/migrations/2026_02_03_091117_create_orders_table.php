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
        Schema::create('pickupPoints', function (Blueprint $table) {
            $table->id();
            $table->string('address');
        });

        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->date('date_to');
            $table->date('date_from');
            $table->bigInteger('pickupPoints_id');
            $table->foreignId('user_id')->constrained();
            $table->integer('code');
            $table->bigInteger('status_id');
        });

        Schema::create('order_items', function (Blueprint $table){
            $table->id();
            $table->foreignId('order_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down():void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('statuses');
        Schema::dropIfExists('pickup_points');
    }
};
