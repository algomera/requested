<?php

	namespace App\Models;

	use App\Traits\Searchable;
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class ProductionOrder extends Model
	{
		use HasFactory, Searchable;

		public function item() {
			return $this->belongsTo(Item::class);
		}

		public function destination() {
			return $this->belongsTo(Destination::class);
		}
	}
