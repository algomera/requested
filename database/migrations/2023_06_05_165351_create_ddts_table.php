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
        Schema::create('ddts', function (Blueprint $table) {
            $table->id();
			$table->foreignIdFor(\App\Models\WarehouseOrder::class, 'warehouse_order_id');
			$table->boolean('generated')->default(false);
	        $table->timestamp('generated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ddts');
    }
};
