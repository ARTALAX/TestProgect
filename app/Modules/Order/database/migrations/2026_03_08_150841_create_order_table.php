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
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId(column: 'user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId(column: 'address_id')->constrained()->onDelete('cascade');
            $table->string(column: 'status')->default('created'); // created, paid, in_progress, delivering, completed, cancelled
            $table->decimal(column: 'total_price')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
