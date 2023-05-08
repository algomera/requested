<?php

	namespace App\Models;

	use App\Traits\Searchable;
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class Log extends Model
	{
		use HasFactory, Searchable;

		public function loggable() {
			return $this->morphTo();
		}

		public function user() {
			return $this->belongsTo(User::class);
		}
	}
