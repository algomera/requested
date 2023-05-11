<?php

	namespace App\Models;

	use App\Traits\Searchable;
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class ProductionOrder extends Model
	{
		use HasFactory, Searchable;

		public function getMaxItemsProduciblesAttribute() {
			$quantities = $this->item->products->map(function ($product) {
				return $product->locations()
					->where('type', 'produzione')
					->pluck('quantity');
			});

			$total = [];
			foreach ($quantities as $item) {
				$total[] = $item->sum();
			}

			$minQuantity = min($total);

			return $minQuantity;
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
