<?php

namespace Modules\OrderItem\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\OrderItem\Models\OrderItem;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderItem::factory()->count(count: 20)->create();
    }
}
