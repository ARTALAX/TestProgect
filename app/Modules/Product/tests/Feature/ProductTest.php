<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Product\Models\Product;
use Modules\User\Models\User;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(closure: function (): void {
    Product::factory()->count(count: 3)->create();
});

it(description: 'allows anyone to get products', closure: function (): void {
    $response = $this->getJson('/api/products');
    $response->assertStatus(200)
        ->assertJsonCount(3, 'data')
    ;
});

it(description: 'prevents unauthenticated user from creating a product', closure: function (): void {
    $response = $this->postJson('/api/products', [
        'name' => 'Test Product',
        'description' => 'Test Desc',
        'price' => 100,
    ]);

    $response->assertStatus(401);
});

it(description: 'prevents non-admin user from creating a product', closure: function (): void {
    $user = User::factory()->create(attributes: ['role' => 'user']);
    $token = auth()->login($user);

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}",
    ])->postJson('/api/products', [
        'name' => 'Test Product',
        'description' => 'Test Desc',
        'price' => 100,
    ]);

    $response->assertStatus(403); // Forbidden
});

it(description: 'allows admin user to create a product', closure: function (): void {
    $admin = User::factory()->create(attributes: ['role' => 'admin']);
    $token = auth()->login($admin);

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}",
    ])->postJson('/api/products', [
        'name' => 'Admin Product',
        'description' => 'Admin Desc',
        'price' => 100,
        'category' => 'pizza',
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('data.name', 'Admin Product')
    ;
});

it(description: 'returns 404 when deleting non-existing product as admin', closure: function (): void {
    $admin = User::factory()->create(attributes: ['role' => 'admin']);
    $token = auth()->login($admin);

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}",
    ])->deleteJson('/api/products/9999'); // несуществующий

    $response->assertStatus(404);
});

it(description: 'allows admin to delete existing product', closure: function (): void {
    $admin = User::factory()->create(attributes: ['role' => 'admin']);
    $token = auth()->login($admin);

    $product = Product::factory()->create();

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}",
    ])->deleteJson("/api/products/{$product->id}");

    $response->assertStatus(204);
    $this->assertDatabaseMissing('products', ['id' => $product->id]);
});
