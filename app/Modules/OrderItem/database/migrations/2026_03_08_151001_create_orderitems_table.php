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
        Schema::create('order_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId(column: 'order_id')->constrained(table: 'orders')->cascadeOnDelete();
            $table->foreignId(column: 'product_id')->constrained(table: 'products')->cascadeOnDelete();
            $table->integer(column: 'quantity')->default(1);
            $table->decimal(column: 'price'); // фиксируем цену на момент заказа
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orderitems');
    }
};
