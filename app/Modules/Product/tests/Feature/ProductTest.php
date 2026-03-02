<?php

use Modules\Product\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('can list products', function () {
    $products = Product::factory()->count(3)->create();

    $response = $this->getJson('/api/products');

    $response->assertStatus(200)
        ->assertJsonCount(3, 'data');
});

it('can show a product', function () {
    $product = Product::factory()->create();

    $response = $this->getJson("/api/products/{$product->id}");

    $response->assertStatus(200)
        ->assertJsonFragment([
            'id' => $product->id,
            'name' => $product->name,
        ]);
});

it('returns 404 for non-existing product', function () {
    $response = $this->getJson('/api/products/999999');

    $response->assertStatus(404);
});

it('can create a product with valid data', function () {
    $response = $this->postJson('/api/products', [
        'name' => 'Test product',
        'description' => 'Some description',
        'price' => 100,
    ]);

    $response->assertStatus(201);

    $this->assertDatabaseHas('products', [
        'name' => 'Test product',
    ]);
});

it('fails to create product with invalid data', function () {
    $response = $this->postJson('/api/products', [
        'name' => '',
        'price'=> ''
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['name','price']);
});

it('can update a product', function () {
    $product = Product::factory()->create();

    $response = $this->putJson("/api/products/{$product->id}", [
        'name' => 'Updated name',
        'price' => $product->price,
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'name' => 'Updated name',
    ]);
});

it('returns 404 when updating non-existing product', function () {
    $response = $this->putJson('/api/products/999999', [
        'name' => 'Updated name',
    ]);

    $response->assertStatus(404);
});

it('can delete a product', function () {
    $product = Product::factory()->create();

    $response = $this->deleteJson("/api/products/{$product->id}");

    $response->assertStatus(204);
    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
    ]);
});

it('returns 404 when deleting non-existing product', function () {
    $response = $this->deleteJson('/api/products/999999');

    $response->assertStatus(404);
});
