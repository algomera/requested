<?php

	namespace App\Models;

	use App\Traits\Searchable;
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class Product extends Model
	{
		use HasFactory, Searchable;

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
