<?php

	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;

	return new class extends Migration {
		/**
		 * Run the migrations.
		 */
		public function up(): void {
			Schema::create('production_orders', function (Blueprint $table) {
				$table->id();
				$table->string('code');
				$table->foreignIdFor(\App\Models\Item::class, 'item_id');
				$table->integer('quantity');
				$table->date('delivery_date');
				$table->foreignIdFor(\App\Models\Destination::class, 'destination_id');
				$table->enum('status', array_keys(config('requested.production_orders.status')));
				$table->timestamps();
			});
		}

		/**
		 * Reverse the migrations.
		 */
		public function down(): void {
			Schema::dropIfExists('production_orders');
		}
	};
