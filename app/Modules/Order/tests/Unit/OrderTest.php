<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Cart\Models\Cart;
use Modules\Order\Models\Order;
use Modules\Product\Models\Product;
use Modules\User\Models\User;
use Tests\TestCase;

uses(RefreshDatabase::class, TestCase::class);

beforeEach(closure: function (): void {
    $this->user = User::factory()->create(attributes: ['role' => 'user']); // <-- роль!
    $this->products = Product::factory()->count(count: 3)->create();
    $this->cart = Cart::factory()->create(attributes: ['user_id' => $this->user->id]);
});

it(description: 'prevents unauthenticated user from creating an order', closure: function (): void {
    $response = $this->postJson('/api/orders', [
        'region' => 'Region',
        'city' => 'City',
        'street' => 'Street',
        'house' => '1',
        'postcode' => '12345',
    ]);

    $response->assertStatus(401);
});

it(description: 'fails to create order with empty cart', closure: function (): void {
    $token = auth()->login($this->user);

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}",
    ])->postJson('/api/orders', [
        'region' => 'Region',
        'city' => 'City',
        'street' => 'Street',
        'house' => '1',
        'postcode' => '12345',
    ]);

    $response->assertStatus(422)
        ->assertJsonFragment(['error' => 'В корзине нет товаров'])
    ;
});

it(description: 'creates an order with valid cart', closure: function (): void {
    $token = auth()->login($this->user);

    // добавляем товар в корзину
    $this->cart->items()->create([
        'product_id' => $this->products->first()->id,
        'quantity' => 1,
    ]);

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}",
    ])->postJson('/api/orders', [
        'region' => 'Region',
        'city' => 'City',
        'street' => 'Street',
        'house' => '1',
        'postcode' => '12345',
    ]);

    $response->assertStatus(201)
        ->assertJsonFragment(['status' => Order::STATUS_CREATED])
    ;

    $this->assertDatabaseHas('orders', ['user_id' => $this->user->id]);
});

it(description: 'does not allow creating order exceeding pizza/drink limits', closure: function (): void {
    $token = auth()->login($this->user);

    foreach ($this->products as $i => $product) {
        $this->cart->items()->create([
            'product_id' => $product->id,
            'quantity' => 0 === $i ? 11 : 21,
        ]);
    }

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}",
    ])->postJson('/api/orders', [
        'region' => 'Region',
        'city' => 'City',
        'street' => 'Street',
        'house' => '1',
        'postcode' => '12345',
    ]);

    $response->assertStatus(422)
        ->assertJsonFragment(['error' => 'Превышен лимит товаров'])
    ;
});

it(description: 'prevents accessing someone else\'s order', closure: function (): void {
    $otherUser = User::factory()->create();
    $order = Order::factory()->create(attributes: ['user_id' => $otherUser->id]);

    $token = auth()->login($this->user);

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}",
    ])->getJson("/api/orders/{$order->id}");

    $response->assertStatus(403)
        ->assertJsonFragment(['error' => 'Доступ запрещен'])
    ;
});
