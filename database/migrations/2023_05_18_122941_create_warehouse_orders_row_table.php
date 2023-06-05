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
        Schema::create('warehouse_order_rows', function (Blueprint $table) {
            $table->id();
	        $table->foreignIdFor(\App\Models\WarehouseOrder::class, 'warehouse_order_id');
	        $table->foreignIdFor(\App\Models\Product::class, 'product_id')->nullable();
			$table->integer('position');
	        $table->foreignIdFor(\App\Models\Location::class, 'pickup_id')->nullable();
	        $table->foreignIdFor(\App\Models\Location::class, 'destination_id')->nullable();
			$table->double('quantity_total');
			$table->double('quantity_processed');
	        $table->enum('status', array_keys(config('requested.warehouse_orders.status')));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_order_rows');
    }
};
