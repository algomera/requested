<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ddt_product', function (Blueprint $table) {
            $table->id();
	        $table->foreignIdFor(\App\Models\Ddt::class, 'ddt_id');
	        $table->foreignIdFor(\App\Models\Product::class, 'product_id')->nullable();
	        $table->foreignIdFor(\App\Models\Serial::class, 'serial_id')->nullable();
	        $table->double('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ddt_product');
    }
};
