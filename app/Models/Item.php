<?php

	namespace App\Models;

	use App\Traits\Searchable;
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class Item extends Model
	{
		use HasFactory, Searchable;

		public function product() {
			return $this->belongsTo(Product::class);
		}

		public function products() {
			return $this->belongsToMany(Product::class)->withPivot('quantity');
		}

		public function logs() {
			return $this->morphMany(Log::class, 'loggable');
		}
	}
