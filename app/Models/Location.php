<?php

	namespace App\Models;

	use App\Traits\Searchable;
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class Location extends Model
	{
		use HasFactory, Searchable;

		public function productQuantity($product_id)
		{
			return $this->products()->where('product_id', $product_id)->first()?->pivot->quantity ?: 0;
		}

		public function products()
		{
			return $this->belongsToMany(Product::class)->withPivot('quantity');
		}

		public function logs()
		{
			return $this->morphMany(Log::class, 'loggable');
		}
	}
