<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeTest extends TestCase
{
    public function test_home_returns_200_and_contains_hirehub_is_running(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('HireHub —');
        $response->assertSee('Modern ATS for Growing Teams');
    }
}
