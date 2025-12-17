<?php

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

describe('Email Verification', function () {
    beforeEach(function () {
        Notification::fake();
    });

    test('registration sends verification email', function () {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'whatsapp_number' => '+6281234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('verification.notice'));
        $response->assertSessionHas('success');

        // Assert notification was sent
        Notification::assertSentTo(
            User::where('email', 'john@example.com')->first(),
            VerifyEmail::class
        );
    });

    test('newly registered user is logged in but not verified', function () {
        $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'whatsapp_number' => '+6281234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'john@example.com')->first();
        expect($user->email_verified_at)->toBeNull();
        $this->assertAuthenticatedAs($user);
    });

    test('unverified user cannot login', function () {
        $user = User::factory()->unverified()->create([
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response = $this->post('/login', [
            'login' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('verification.notice'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    });

    test('verified user can login', function () {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'password123',
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'login' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/account');
        $this->assertAuthenticatedAs($user);
    });

    test('authenticated user can access verification notice page', function () {
        $user = User::factory()->unverified()->create();
        $this->actingAs($user);

        $response = $this->get(route('verification.notice'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.verify-email');
    });

    test('guest cannot access verification notice page', function () {
        $response = $this->get(route('verification.notice'));

        $response->assertRedirect(route('login'));
    });

    test('user can verify email with valid link', function () {
        $user = User::factory()->unverified()->create();
        $this->actingAs($user);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->get($verificationUrl);

        $response->assertRedirect(route('account'));
        $response->assertSessionHas('success');

        $user->refresh();
        expect($user->email_verified_at)->not->toBeNull();
    });

    test('verification link fails with invalid hash', function () {
        $user = User::factory()->unverified()->create();
        $this->actingAs($user);

        $invalidUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => 'invalid-hash']
        );

        $response = $this->get($invalidUrl);

        $response->assertStatus(403);

        $user->refresh();
        expect($user->email_verified_at)->toBeNull();
    });

    test('verification link expires after time limit', function () {
        $user = User::factory()->unverified()->create();
        $this->actingAs($user);

        // Create an expired link (signed 61 minutes ago)
        $expiredUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->subMinutes(61),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->get($expiredUrl);

        $response->assertStatus(403);

        $user->refresh();
        expect($user->email_verified_at)->toBeNull();
    });

    test('user can resend verification email', function () {
        $user = User::factory()->unverified()->create();
        $this->actingAs($user);

        $response = $this->post(route('verification.resend'));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        Notification::assertSentTo($user, VerifyEmail::class);
    });

    test('resend verification is rate limited', function () {
        $user = User::factory()->unverified()->create();
        $this->actingAs($user);

        // First request should succeed
        $this->post(route('verification.resend'))
            ->assertSessionHas('success');

        // Second immediate request should be rate limited
        $response = $this->post(route('verification.resend'));

        $response->assertSessionHasErrors('email');
        expect($response->getSession()->get('errors')->first('email'))
            ->toContain('Please wait');
    });

    test('verified user redirected from resend verification', function () {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $this->actingAs($user);

        $response = $this->post(route('verification.resend'));

        $response->assertRedirect(route('account'));
        $response->assertSessionHas('info');

        // Should not send notification
        Notification::assertNothingSent();
    });

    test('guest cannot resend verification email', function () {
        $response = $this->post(route('verification.resend'));

        $response->assertRedirect(route('login'));
    });

    test('already verified email does not change on re-verification', function () {
        $originalTime = now()->subDays(5);
        $user = User::factory()->create([
            'email_verified_at' => $originalTime,
        ]);
        $this->actingAs($user);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $this->get($verificationUrl);

        $user->refresh();
        // Email verification time should not change
        expect($user->email_verified_at->timestamp)->toBe($originalTime->timestamp);
    });
});
