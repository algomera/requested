<?php

	namespace App\Models;

	use App\Traits\Searchable;
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class ProductionOrder extends Model
	{
		use HasFactory, Searchable;

		public function getMaxItemsProduciblesAttribute() {
			$products = $this->item->products;
			$total = [];
			foreach ($products as $product) {
				$quantities = $product->locations()
					->where('type', 'produzione')
					->sum('quantity');

				$total[] = $quantities / $product->pivot->quantity;
			}

			$minQuantity = min($total);

			return (int) floor($minQuantity);
		}

		public function item() {
			return $this->belongsTo(Item::class);
		}

		public function destination() {
			return $this->belongsTo(Destination::class);
		}

		public function serials() {
			return $this->hasMany(Serial::class);
		}

		public function logs() {
			return $this->morphMany(Log::class, 'loggable');
		}
	}
