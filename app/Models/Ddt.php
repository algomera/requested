<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class Ddt extends Model
	{
		use HasFactory;

		protected $casts = [
			'generated_at' => 'datetime',
		];

		protected static function boot()
		{
			parent::boot();

			static::creating(function ($model) {
				$latestDdt = static::latest()->first();
				$sequence = $latestDdt ? static::count() + 1 : 1;
				$model->code = env('DDT_PREFIX') . $sequence . env('DDT_SUFFIX');
			});
		}

		public function warehouse_order() {
			return $this->belongsTo(WarehouseOrder::class);
		}

		public function products()
		{
			return $this->belongsToMany(Product::class, 'ddt_product', 'ddt_id', 'product_id')->withPivot('quantity');
		}
		public function serials()
		{
			return $this->belongsToMany(Serial::class, 'ddt_product', 'ddt_id', 'serial_id');
		}
	}
