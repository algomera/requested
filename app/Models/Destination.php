<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory, Searchable;

	public function logs() {
		return $this->morphMany(Log::class, 'loggable');
	}
}
