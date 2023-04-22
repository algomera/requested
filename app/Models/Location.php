<?php

	namespace App\Models;

	use App\Traits\Searchable;
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class Location extends Model
	{
		use HasFactory, Searchable;

		public function products() {
			return $this->belongsToMany(Product::class)->distinct();
		}
	}
