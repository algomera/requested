<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseOrderRow extends Model
{
    use HasFactory, Searchable;

	public function product() {
		return $this->belongsTo(Product::class);
	}

	public function pickup() {
		return $this->belongsTo(Location::class, 'pickup_id');
	}

	public function destination() {
		return $this->belongsTo(Location::class, 'destination_id');
	}

	public function warehouse_order() {
		return $this->belongsTo(WarehouseOrder::class);
	}
}
