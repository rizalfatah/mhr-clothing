<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Registration Tests
describe('User Registration', function () {
    test('user can register with valid data', function () {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john1@example.com',
            'whatsapp_number' => '+6281234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john1@example.com',
            'whatsapp_number' => '6281234567890',
        ]);
    });

    test('registration requires name', function () {
        $response = $this->post('/register', [
            'email' => 'john@example.com',
            'whatsapp_number' => '+1234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('name');
    });

    test('registration requires email', function () {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'whatsapp_number' => '+1234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    });

    test('registration requires valid email format', function () {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'whatsapp_number' => '+1234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    });

    test('registration requires whatsapp number', function () {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('whatsapp_number');
    });

    test('registration requires password', function () {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'whatsapp_number' => '+1234567890',
        ]);

        $response->assertSessionHasErrors('password');
    });

    test('password must be at least 8 characters', function () {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'whatsapp_number' => '+1234567890',
            'password' => 'pass',
            'password_confirmation' => 'pass',
        ]);

        $response->assertSessionHasErrors('password');
    });

    test('password confirmation must match', function () {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'whatsapp_number' => '+1234567890',
            'password' => 'password123',
            'password_confirmation' => 'different123',
        ]);

        $response->assertSessionHasErrors('password');
    });

    test('email must be unique', function () {
        User::factory()->create([
            'name' => 'User 1',
            'email' => 'user@example.com', // make it same
            'whatsapp_number' => '+6281234567890',
            'password' => 'password123',
        ]);

        $response = $this->post('/register', [
            'name' => 'User 2',
            'email' => 'user@example.com', // make it same
            'whatsapp_number' => '+62877777777',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    });

    test('whatsapp number must be unique', function () {
        User::factory()->create([
            'whatsapp_number' => '+1234567890',
        ]);

        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'whatsapp_number' => '+1234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('whatsapp_number');
    });

    test('password is hashed when stored', function () {
        $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'whatsapp_number' => '+1234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'john@example.com')->first();
        expect(Hash::check('password123', $user->password))->toBeTrue();
    });
});

// Login Tests
describe('User Login', function () {
    beforeEach(function () {
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'whatsapp_number' => '6281234567890',
            'password' => 'password123',
        ]);
    });

    test('user can login with email', function () {
        $response = $this->post('/login', [
            'login' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/account');
        $this->assertAuthenticatedAs($this->user);
    });

    test('user can login with whatsapp number with leading zero', function () {
        $response = $this->post('/login', [
            'login' => '081234567890',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/account');
        $this->assertAuthenticatedAs($this->user);
    });

    test('user can login with whatsapp number with country code', function () {
        $response = $this->post('/login', [
            'login' => '+6281234567890',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/account');
        $this->assertAuthenticatedAs($this->user);
    });

    test('login fails with incorrect password', function () {
        $response = $this->post('/login', [
            'login' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    });

    test('login fails with non-existent email', function () {
        $response = $this->post('/login', [
            'login' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    });

    test('login requires login field', function () {
        $response = $this->post('/login', [
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('login');
    });

    test('login requires password field', function () {
        $response = $this->post('/login', [
            'login' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('password');
    });

    test('remember me functionality works', function () {
        $response = $this->post('/login', [
            'login' => 'test@example.com',
            'password' => 'password123',
            'remember' => true,
        ]);

        $response->assertRedirect('/account');
        $this->assertAuthenticatedAs($this->user);

        // Check that remember token is set
        $this->user->refresh();
        expect($this->user->remember_token)->not->toBeNull();
    });

    test('session is regenerated on successful login', function () {
        $response = $this->post('/login', [
            'login' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasNoErrors();
        expect(session()->getId())->not->toBeNull();
    });

    test('login input is preserved on failed attempt', function () {
        $response = $this->post('/login', [
            'login' => 'test@example.com',
            'password' => 'wrongpassword',
            'remember' => true,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasInput('login', 'test@example.com');
        $response->assertSessionHasInput('remember');
    });
});

// Logout Tests
describe('User Logout', function () {
    test('authenticated user can logout', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    });

    test('session is invalidated on logout', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $sessionId = session()->getId();

        $this->post('/logout');

        expect(session()->getId())->not->toBe($sessionId);
    });

    test('guest cannot access logout', function () {
        $response = $this->post('/logout');

        $response->assertRedirect('/login');
    });
});

// Guest Middleware Tests
describe('Authentication Middleware', function () {
    test('guest can access login page', function () {
        $response = $this->get('/login');

        $response->assertStatus(200);
    });

    test('guest can access register page', function () {
        $response = $this->get('/register');

        $response->assertStatus(200);
    });

    test('authenticated user cannot access login page', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/login');

        $response->assertRedirect('/');
    });

    test('authenticated user cannot access register page', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/register');

        $response->assertRedirect('/');
    });

    test('authenticated user can access account page', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/account');

        $response->assertStatus(200);
    });
});
