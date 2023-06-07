<?php

	namespace App\Models;

	use App\Traits\Searchable;
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class WarehouseOrder extends Model
	{
		use HasFactory, Searchable;

		public function getStatus()
		{
			$rows = $this->rows;
			$statuses = $rows->pluck('status')->unique();

			if ($statuses->count() === 1 && $statuses->first() === 'to_transfer') {
				return 'to_transfer';
			} elseif ($statuses->contains('partially_transferred') || $statuses->contains('to_transfer')) {
				return 'partially_transferred';
			} elseif ($statuses->contains('transferred')) {
				return 'transferred';
			}
		}

		public function ddts() {
			return $this->hasMany(Ddt::class);
		}

		public function destination() {
			return $this->belongsTo(Location::class, 'destination_id');
		}

		public function logs()
		{
			return $this->morphMany(Log::class, 'loggable');
		}

		public function production_order() {
			return $this->belongsTo(ProductionOrder::class);
		}

		public function rows() {
			return $this->hasMany(WarehouseOrderRow::class);
		}

		public function serials()
		{
			return $this->hasMany(Serial::class);
		}
	}
