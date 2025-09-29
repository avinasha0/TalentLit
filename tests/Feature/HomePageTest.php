<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_home_page_returns_200_and_shows_hirehub()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('HireHub');
        $response->assertSee('Modern ATS for Growing Teams');
    }
}
