<?php

namespace Tests\Feature;

use App\Models\Absence;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_user_index_log_out(): void
    {
        $response = $this->get(route('user.index'));

        $response->assertStatus(302);
    }

    public function test_user_index_log_in(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('user.index'));

        $response->assertStatus(200);
    }

    public function test_create_user_log_out()
    {

        $response = $this->get(route('user.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_create_user_log_in()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('user.create'));
        $response->assertRedirect(route('user.index'));
    }

    public function test_edit_user_log_out()
    {
        $user = User::factory()->create();
        $response = $this->get(route('user.edit', $user->id));
        $response->assertRedirect(route('login'));
    }

    public function test_edit_user_log_in()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('user.edit', $user->id));
        $response->assertStatus(200);
    }

    public function test_edit_user_log_in_as_admin()
    {
        $user = User::factory()->create();
        $user->assign('admin');
        $anotherUser = User::factory()->create();
        $response = $this->actingAs($user)->get(route('user.edit', ['user' => $anotherUser]));
        $response->assertStatus(200);
    }

    public function test_show_user_logout()
    {
        $user = User::factory()->create();
        $response = $this->get(route('user.show', $user));
        $response->assertRedirect(route('login'));
    }

    public function test_show_user_login()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('user.show', $user));
        $response->assertStatus(302);
    }

    public function test_show_user_admin()
    {
        $user = User::factory()->create(['isAdmin' => true]);
        $user->assign('admin');
        $anotherUser = User::factory()->create();
        $absence = Absence::factory()->create(['user_id_salarie' => $anotherUser->id]);
        $response = $this->actingAs($user)->get(route('user.show', $anotherUser));
        $response->assertStatus(200);
    }

    public function test_update_user_log_out()
    {
        $user = User::factory()->create();
        $response = $this->put(route('user.update', $user->id), ['name' => 'test']);
        $response->assertRedirect(route('login'))->assertSessionHas('error', '');
    }

    public function test_update_user_log_in()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->put(route('user.update', $user->id), ['nom' => 'test', 'prenom' => 'test', 'email' => 'test@example.com']);
        $response->assertRedirect(route('user.index'));
    }
}
