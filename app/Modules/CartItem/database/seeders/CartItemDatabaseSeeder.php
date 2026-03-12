<?php

namespace Modules\CartItem\Database\Seeders;

use Illuminate\Database\Seeder;

class CartItemDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(class: [CartItemSeeder::class]);
    }
}
