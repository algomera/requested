<?php

	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;

	return new class extends Migration {
		/**
		 * Run the migrations.
		 */
		public function up(): void
		{
			Schema::create('production_order_materials', function (Blueprint $table) {
				$table->id();
				$table->foreignIdFor(\App\Models\ProductionOrder::class, 'production_order_id');
				$table->foreignIdFor(\App\Models\Product::class, 'product_id');
				$table->foreignIdFor(\App\Models\Location::class, 'location_id');
				$table->integer('position')->nullable();
				$table->double('quantity');
			});
		}

		/**
		 * Reverse the migrations.
		 */
		public function down(): void
		{
			Schema::dropIfExists('production_order_materials');
		}
	};
