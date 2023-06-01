<?php

	namespace App\Models;

	use App\Traits\Searchable;
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class Product extends Model
	{
		use HasFactory, Searchable;

		function decimalSteps() {
			if ($this->unit->decimals >= 0) {
				$value = 1 / pow(10, $this->unit->decimals);
				return number_format($value, $this->unit->decimals, '.', '');
			} else {
				return 1;
			}
		}

		public function unit() {
			return $this->belongsTo(Unit::class);
		}

		public function locations() {
			return $this->belongsToMany(Location::class)->withPivot('quantity');
		}

		public function items() {
			return $this->belongsToMany(Item::class);
		}

		public function logs() {
			return $this->morphMany(Log::class, 'loggable');
		}
	}
