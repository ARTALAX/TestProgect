<?php

use Modules\Product\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\Models\User;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    Product::factory()->count(3)->create();
});

it('allows anyone to get products', function () {
    $response = $this->getJson('/api/products');
    $response->assertStatus(200)
        ->assertJsonCount(3, 'data');
});

it('prevents unauthenticated user from creating a product', function () {
    $response = $this->postJson('/api/products', [
        'name' => 'Test Product',
        'description' => 'Test Desc',
        'price'=> 100
    ]);

    $response->assertStatus(401);
});

it('prevents non-admin user from creating a product', function () {
    $user = User::factory()->create(['role' => 'user']);
    $token = auth()->login($user);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->postJson('/api/products', [
        'name' => 'Test Product',
        'description' => 'Test Desc',
        'price'=> 100
    ]);

    $response->assertStatus(403); // Forbidden
});

it('allows admin user to create a product', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $token = auth()->login($admin);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->postJson('/api/products', [
        'name' => 'Admin Product',
        'description' => 'Admin Desc',
        'price' => 100
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('data.name', 'Admin Product');
});

it('returns 404 when deleting non-existing product as admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $token = auth()->login($admin);

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->deleteJson('/api/products/9999'); // несуществующий

    $response->assertStatus(404);
});

it('allows admin to delete existing product', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $token = auth()->login($admin);

    $product = Product::factory()->create();

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->deleteJson("/api/products/{$product->id}");

    $response->assertStatus(204);
    $this->assertDatabaseMissing('products', ['id' => $product->id]);
});

