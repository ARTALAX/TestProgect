<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId(column: 'cart_id')->constrained(table: 'carts')->cascadeOnDelete();
            $table->foreignId(column: 'product_id')->constrained(table: 'products')->cascadeOnDelete();
            $table->integer(column: 'quantity')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cartitem');
    }
};
