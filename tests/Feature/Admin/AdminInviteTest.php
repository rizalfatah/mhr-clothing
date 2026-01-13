<?php

namespace Tests\Feature\Admin;

use App\Mail\AdminInviteMail;
use App\Models\AdminInvite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminInviteTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_invite_list(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.invites.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.invites.index');
    }

    public function test_admin_can_view_invite_create_form(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.invites.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.invites.create');
    }

    public function test_admin_can_send_invite(): void
    {
        Mail::fake();

        $response = $this->actingAs($this->admin)->post(route('admin.invites.store'), [
            'email' => 'newadmin@example.com',
        ]);

        $response->assertRedirect(route('admin.invites.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('admin_invites', [
            'email' => 'newadmin@example.com',
            'invited_by' => $this->admin->id,
        ]);

        Mail::assertQueued(AdminInviteMail::class, function ($mail) {
            return $mail->invite->email === 'newadmin@example.com';
        });
    }

    public function test_cannot_invite_existing_user_email(): void
    {
        $existingUser = User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->actingAs($this->admin)->post(route('admin.invites.store'), [
            'email' => 'existing@example.com',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('admin_invites', [
            'email' => 'existing@example.com',
        ]);
    }

    public function test_cannot_send_duplicate_pending_invite(): void
    {
        Mail::fake();

        // First invite
        $this->actingAs($this->admin)->post(route('admin.invites.store'), [
            'email' => 'newadmin@example.com',
        ]);

        // Second invite to same email
        $response = $this->actingAs($this->admin)->post(route('admin.invites.store'), [
            'email' => 'newadmin@example.com',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseCount('admin_invites', 1);
    }

    public function test_admin_can_revoke_pending_invite(): void
    {
        $invite = AdminInvite::create([
            'email' => 'pending@example.com',
            'token' => 'test-token-123',
            'invited_by' => $this->admin->id,
            'expires_at' => now()->addHours(48),
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.invites.revoke', $invite));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('admin_invites', ['id' => $invite->id]);
    }

    public function test_guest_can_access_invite_accept_page(): void
    {
        $invite = AdminInvite::create([
            'email' => 'newadmin@example.com',
            'token' => 'valid-token-123',
            'invited_by' => $this->admin->id,
            'expires_at' => now()->addHours(48),
        ]);

        $response = $this->get(route('admin.invite.accept', $invite->token));

        $response->assertStatus(200);
        $response->assertViewIs('admin.invite-accept');
    }

    public function test_guest_cannot_access_expired_invite(): void
    {
        $invite = AdminInvite::create([
            'email' => 'newadmin@example.com',
            'token' => 'expired-token-123',
            'invited_by' => $this->admin->id,
            'expires_at' => now()->subHour(),
        ]);

        $response = $this->get(route('admin.invite.accept', $invite->token));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
    }

    public function test_guest_cannot_access_invalid_invite(): void
    {
        $response = $this->get(route('admin.invite.accept', 'invalid-token'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
    }

    public function test_guest_can_complete_registration_via_invite(): void
    {
        $invite = AdminInvite::create([
            'email' => 'newadmin@example.com',
            'token' => 'valid-token-456',
            'invited_by' => $this->admin->id,
            'expires_at' => now()->addHours(48),
        ]);

        $response = $this->post(route('admin.invite.accept.store', $invite->token), [
            'name' => 'New Admin User',
            'whatsapp_number' => '081234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        // Check user was created with admin role
        $this->assertDatabaseHas('users', [
            'email' => 'newadmin@example.com',
            'name' => 'New Admin User',
            'role' => 'admin',
        ]);

        // Check invite is marked as accepted
        $invite->refresh();
        $this->assertNotNull($invite->accepted_at);
    }

    public function test_guest_cannot_use_same_whatsapp_number(): void
    {
        $existingUser = User::factory()->create(['whatsapp_number' => '081234567890']);

        $invite = AdminInvite::create([
            'email' => 'newadmin@example.com',
            'token' => 'valid-token-789',
            'invited_by' => $this->admin->id,
            'expires_at' => now()->addHours(48),
        ]);

        $response = $this->post(route('admin.invite.accept.store', $invite->token), [
            'name' => 'New Admin User',
            'whatsapp_number' => '081234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('whatsapp_number');
    }
}
