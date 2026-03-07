<?php

use Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('can register a new user', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['token']);

    // Проверяем, что пользователь появился в базе
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'name' => 'Test User',
    ]);
});

it('can login with correct credentials', function () {
    // Создаём пользователя
    $user = User::factory()->create([
        'email' => 'login@example.com',
        'password' => bcrypt('secret123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'login@example.com',
        'password' => 'secret123',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['token']);
});

it('cannot login with wrong credentials', function () {
    $user = User::factory()->create([
        'email' => 'fail@example.com',
        'password' => bcrypt('correctpass'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'fail@example.com',
        'password' => 'wrongpass',
    ]);

    $response->assertStatus(401)
        ->assertJson(['error' => 'Unauthorized']);
});
