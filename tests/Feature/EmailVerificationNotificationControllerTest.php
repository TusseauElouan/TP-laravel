<?php

namespace Tests\Feature;

use Tests\TestCase;

class EmailVerificationNotificationControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_log_out_store(): void
    {
        $response = $this->post(route('verification.send'));

        $response->assertRedirect(route('login'))
        ->assertSessionHas("error", '');
    }
}
