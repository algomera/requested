<?php

	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;

	return new class extends Migration {
		/**
		 * Run the migrations.
		 */
		public function up(): void {
			Schema::create('location_product', function (Blueprint $table) {
				$table->foreignIdFor(\App\Models\Location::class, 'location_id');
				$table->foreignIdFor(\App\Models\Product::class, 'product_id');
				$table->float('quantity');
			});
		}

		/**
		 * Reverse the migrations.
		 */
		public function down(): void {
			Schema::dropIfExists('location_product');
		}
	};
