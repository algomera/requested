<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serial extends Model
{
    use HasFactory, Searchable;

	public $timestamps = false;

	public function production_order() {
		return $this->belongsTo(ProductionOrder::class);
	}

	public function logs() {
		return $this->morphMany(Log::class, 'loggable');
	}
}
