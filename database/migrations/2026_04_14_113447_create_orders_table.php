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
        Schema::create('pickup_points', function (Blueprint $table) {
            $table->id();
            $table->string('address');
        });

        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->date('date_order');
            $table->date('date_delivery');
            $table->foreignId('pickup_point_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->string('code');
            $table->foreignId('status_id')->constrained();
        });

        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orders')->constrained();
            $table->foreignId('products')->constrained();
            $table->unsignedInteger('quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
        Schema::dropIfExists('statuses');
        Schema::dropIfExists('pickup_points');
        Schema::dropIfExists('orders');
    }
};
