<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class ProductionOrderMaterial extends Model
	{
		use HasFactory;

		public $timestamps = false;

		public function production_order() {
			return $this->belongsTo(ProductionOrder::class);
		}

		public function product() {
			return $this->belongsTo(Product::class);
		}
	}
