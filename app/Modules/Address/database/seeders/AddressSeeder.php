<?php

namespace Modules\Address\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Address\Models\Address;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Address::factory()->count(count: 20)->create();
    }
}
