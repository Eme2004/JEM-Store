<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_profile(): void
    {
        $response = $this->get(route('profile.show'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_profile(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('profile.show'));

        $response->assertOk();
        $response->assertSee($user->name);
        $response->assertSee($user->email);
    }

    public function test_user_can_update_profile(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->put(route('profile.update'), [
                'name' => 'Usuario Actualizado',
                'email' => 'actualizado@example.com',
            ]);

        $response->assertRedirect(route('profile.show'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Usuario Actualizado',
            'email' => 'actualizado@example.com',
        ]);
    }

    public function test_user_cannot_use_another_users_email(): void
    {
        $user = User::factory()->create([
            'email' => 'usuario@example.com',
        ]);

        $otherUser = User::factory()->create([
            'email' => 'otro@example.com',
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('profile.update'), [
                'name' => $user->name,
                'email' => $otherUser->email,
            ]);

        $response->assertSessionHasErrors('email');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'usuario@example.com',
        ]);
    }
}