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
        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->string(column: 'name');
            $table->text(column: 'description')->nullable();
            $table->decimal(column: 'price');
            $table->decimal(column: 'weight')->nullable();
            $table->enum(column: 'category', allowed: ['pizza', 'drink']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
