<?php

namespace Modules\Cart\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Cart\Models\Cart;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cart::factory()->count(count: 30)->create();
    }
}
