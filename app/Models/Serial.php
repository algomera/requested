<?php

	namespace App\Models;

	use App\Traits\Searchable;
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class Serial extends Model
	{
		use HasFactory, Searchable;

		public $timestamps = false;

		protected $casts = [
			'completed_at' => 'datetime',
			'shipped_at' => 'datetime',
			'received_at' => 'datetime'
		];

		public function ddts()
		{
			return $this->belongsToMany(Ddt::class, 'ddt_product', 'serial_id', 'ddt_id');
		}

		public function product()
		{
			return $this->belongsTo(Product::class);
		}

		public function production_order()
		{
			return $this->belongsTo(ProductionOrder::class);
		}

		public function logs()
		{
			return $this->morphMany(Log::class, 'loggable');
		}
	}
