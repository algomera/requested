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
        Schema::create('production_orders_products', function (Blueprint $table) {
            $table->id();
			$table->foreignIdFor(\App\Models\ProductionOrder::class, 'production_order_id');
			$table->foreignIdFor(\App\Models\Product::class, 'product_id');
			$table->float('quantity_needed');
			$table->float('quantity_transferred');
	        $table->enum('status', array_keys(config('requested.warehouse_orders.status')));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_orders_products');
    }
};
