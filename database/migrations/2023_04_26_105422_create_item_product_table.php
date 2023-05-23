<?php

	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;

	return new class extends Migration {
		/**
		 * Run the migrations.
		 */
		public function up(): void {
			Schema::create('item_product', function (Blueprint $table) {
				$table->foreignIdFor(\App\Models\Item::class, 'item_id');
				$table->foreignIdFor(\App\Models\Product::class, 'product_id');
				$table->integer('position')->nullable();
				$table->double('quantity');
			});
		}

		/**
		 * Reverse the migrations.
		 */
		public function down(): void {
			Schema::dropIfExists('item_product');
		}
	};
