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
        Schema::create('warehouse_orders', function (Blueprint $table) {
            $table->id();
			$table->foreignIdFor(\App\Models\ProductionOrder::class, 'production_order_id');
			$table->foreignIdFor(\App\Models\Location::class, 'destination_id')->nullable();
	        $table->enum('type', array_keys(config('requested.warehouse_orders.types')));
			$table->string('reason');
			$table->foreignIdFor(\App\Models\User::class, 'user_id')->nullable();
			$table->boolean('system')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_orders');
    }
};
