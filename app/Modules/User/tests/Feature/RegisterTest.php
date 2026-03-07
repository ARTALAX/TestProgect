<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\Models\User;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it(description: 'can register a new user', closure: function (): void {
    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['token'])
    ;

    // Проверяем, что пользователь появился в базе
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'name' => 'Test User',
    ]);
});

it(description: 'can login with correct credentials', closure: function (): void {
    // Создаём пользователя
    $user = User::factory()->create(attributes: [
        'email' => 'login@example.com',
        'password' => bcrypt(value: 'secret123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'login@example.com',
        'password' => 'secret123',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['token'])
    ;
});

it(description: 'cannot login with wrong credentials', closure: function (): void {
    $user = User::factory()->create(attributes: [
        'email' => 'fail@example.com',
        'password' => bcrypt(value: 'correctpass'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'fail@example.com',
        'password' => 'wrongpass',
    ]);

    $response->assertStatus(401)
        ->assertJson(['error' => 'Unauthorized'])
    ;
});
