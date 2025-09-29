<?php

namespace Tests\Feature;

use Tests\TestCase;

class UIComponentsTest extends TestCase
{
    public function test_home_page_has_modern_design()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('HireHub —');
        $response->assertSee('Modern ATS for Growing Teams');
        $response->assertSee('View Careers');
        $response->assertSee('Sign In');
    }

    public function test_home_page_has_features_section()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Post Jobs');
        $response->assertSee('Track Candidates');
        $response->assertSee('Automate Workflows');
    }

    public function test_home_page_has_stats_section()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Faster Time-to-Hire');
        $response->assertSee('Candidates Processed');
        $response->assertSee('Hiring Manager Satisfaction');
    }
}
