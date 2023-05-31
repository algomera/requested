<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseOrderRow extends Model
{
    use HasFactory;

	public function product() {
		return $this->belongsTo(Product::class);
	}

	public function pickup() {
		return $this->belongsTo(Location::class, 'pickup_id');
	}

	public function warehouse_order() {
		return $this->belongsTo(WarehouseOrder::class);
	}
}
