<?php

	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;

	return new class extends Migration {
		/**
		 * Run the migrations.
		 */
		public function up(): void {
			Schema::create('serials', function (Blueprint $table) {
				$table->id();
				$table->foreignIdFor(\App\Models\ProductionOrder::class, 'production_order_id')->nullable();
				$table->foreignIdFor(\App\Models\WarehouseOrder::class, 'warehouse_order_id')->nullable();
				$table->foreignIdFor(\App\Models\Product::class, 'product_id');
				$table->string('code');
				$table->boolean('completed')->default(false);
				$table->timestamp('completed_at')->nullable();
				$table->boolean('shipped')->default(false);
				$table->timestamp('shipped_at')->nullable();
				$table->boolean('received')->default(false);
				$table->timestamp('received_at')->nullable();
			});
		}

		/**
		 * Reverse the migrations.
		 */
		public function down(): void {
			Schema::dropIfExists('serials');
		}
	};
