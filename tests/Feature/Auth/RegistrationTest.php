<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        Mail::fake();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'g-recaptcha-response' => 'test-token', // Mock reCAPTCHA
        ]);

        // User should not be authenticated yet (needs email activation)
        $this->assertGuest();
        
        // Should redirect to login with success message
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        // User should be created but not verified
        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
        $this->assertNull($user->email_verified_at);
        $this->assertNotNull($user->activation_token);

        // Activation email should be sent
        Mail::assertSent(\App\Mail\EmailActivationMail::class);
    }

    public function test_user_can_reregister_with_same_email_if_unverified(): void
    {
        Mail::fake();

        // Create an unverified user
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'email_verified_at' => null,
        ]);

        // Try to register again with same email
        $response = $this->post('/register', [
            'name' => 'Updated Name',
            'email' => 'test@example.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
            'g-recaptcha-response' => 'test-token',
        ]);

        // Should succeed and update the existing user
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        // User should be updated
        $user->refresh();
        $this->assertEquals('Updated Name', $user->name);
        $this->assertNull($user->email_verified_at);
        $this->assertNotNull($user->activation_token);

        // Activation email should be sent
        Mail::assertSent(\App\Mail\EmailActivationMail::class);
    }

    public function test_user_cannot_register_with_verified_email(): void
    {
        // Create a verified user
        User::factory()->create([
            'email' => 'test@example.com',
            'email_verified_at' => now(),
        ]);

        // Try to register with same email
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'g-recaptcha-response' => 'test-token',
        ]);

        // Should fail with validation error
        $response->assertSessionHasErrors(['email']);
    }
}
