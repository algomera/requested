<?php

	namespace App\Models;

	use App\Traits\Searchable;
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class ProductionOrder extends Model
	{
		use HasFactory, Searchable;

		public function getMaxItemsProduciblesAttribute()
		{
			$products = $this->item->products()->with('locations')->get();
			$total = [];
			foreach ($products as $product) {
				$quantities = $product->locations
					->where('type', 'produzione')
					->sum('quantity');

				$total[] = $quantities / $product->pivot->quantity;
			}

			$minQuantity = min($total);

			return (int)floor($minQuantity);
		}

		public function getWarehouseOrderStatus()
		{
			$products = $this->warehouse_order_products;
			$statuses = $products->pluck('pivot.status')->unique();

			if ($statuses->count() === 1 && $statuses->first() === 'to_transfer') {
				return 'to_transfer';
			} elseif ($statuses->contains('partially_transferred') || $statuses->contains('to_transfer')) {
				return 'partially_transferred';
			} elseif ($statuses->contains('transferred')) {
				return 'transferred';
			}
		}


		public function item()
		{
			return $this->belongsTo(Item::class);
		}

		public function destination()
		{
			return $this->belongsTo(Destination::class);
		}

		public function serials()
		{
			return $this->hasMany(Serial::class);
		}

		public function logs()
		{
			return $this->morphMany(Log::class, 'loggable');
		}

		public function warehouse_order_products()
		{
			return $this->belongsToMany(Product::class, 'production_orders_products')->withPivot(['quantity_needed', 'quantity_transferred', 'status']);
		}
	}
