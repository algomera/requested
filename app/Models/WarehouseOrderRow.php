<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseOrderRow extends Model
{
    use HasFactory;

	public function warehouse_order() {
		return $this->belongsTo(WarehouseOrder::class);
	}
}
