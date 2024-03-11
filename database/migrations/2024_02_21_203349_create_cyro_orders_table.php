<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cyro_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cartpanda_order_id');
            $table->timestamp('new_order_sent');
            $table->timestamp('new_order_processed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cyro_orders');
    }
};
