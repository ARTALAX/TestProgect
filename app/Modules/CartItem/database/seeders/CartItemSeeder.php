<?php

namespace Modules\CartItem\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\CartItem\Models\CartItem;

class CartItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CartItem::factory()->count(count: 20)->create();
    }
}
