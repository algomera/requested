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
					->sum('pivot.quantity');

				$total[] = $quantities / $product->pivot->quantity;
			}

			$minQuantity = min($total);

			return (int)floor($minQuantity);
		}


		public function item()
		{
			return $this->belongsTo(Item::class);
		}

		public function destination()
		{
			return $this->belongsTo(Location::class);
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

		public function materials() {
			return $this->hasMany(ProductionOrderMaterial::class);
		}
	}
