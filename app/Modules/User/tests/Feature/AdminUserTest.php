<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\Enums\UserRole;
use Modules\User\Models\User;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it(description: 'prevents unauthenticated user from viewing users list', closure: function (): void {
    $response = $this->getJson('/api/users');

    $response->assertStatus(401);
});

it(description: 'prevents nonAdmin user from viewing users list', closure: function (): void {
    $user = User::factory()->create(attributes: ['role' => UserRole::USER]);
    $token = auth()->login($user);

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}",
    ])->getJson('/api/users');

    $response->assertStatus(403);
});

it(description: 'allows admin user to view users list', closure: function (): void {
    $admin = User::factory()->create(attributes: ['role' => UserRole::ADMIN]);
    User::factory()->count(count: 16)->create();
    $token = auth()->login($admin);

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}",
    ])->getJson('/api/users');

    $response->assertStatus(200)
        ->assertJsonCount(15, 'data')
        ->assertJsonPath('total', 17)
        ->assertJsonPath('per_page', 15)
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'email', 'role', 'created_at', 'updated_at'],
            ],
        ])
    ;
});

it(description: 'allows admin user to update role', closure: function (): void {
    $admin = User::factory()->create(attributes: ['role' => UserRole::ADMIN]);
    $user = User::factory()->create(attributes: ['role' => UserRole::USER]);
    $token = auth()->login($admin);

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}",
    ])->patchJson("/api/users/{$user->id}/role", [
        'role' => UserRole::ADMIN->value,
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('role', UserRole::ADMIN->value)
    ;

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'role' => UserRole::ADMIN->value,
    ]);
});

it(description: 'returns validation error for invalid role', closure: function (): void {
    $admin = User::factory()->create(attributes: ['role' => UserRole::ADMIN]);
    $user = User::factory()->create(attributes: ['role' => UserRole::USER]);
    $token = auth()->login($admin);

    $response = $this->withHeaders([
        'Authorization' => "Bearer {$token}",
    ])->patchJson("/api/users/{$user->id}/role", [
        'role' => 'super-admin',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['role'])
    ;
});
