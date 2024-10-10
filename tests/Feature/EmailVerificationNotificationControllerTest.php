<?php

namespace Tests\Feature;

use Tests\TestCase;

class EmailVerificationNotificationControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('accueil');

        $response->assertStatus(302);
    }
}
