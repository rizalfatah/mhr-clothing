<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminInviteMail;
use App\Models\AdminInvite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AdminInviteController extends Controller
{
    /**
     * Display list of all invites
     */
    public function index()
    {
        $invites = AdminInvite::with('inviter')
            ->orderByDesc('created_at')
            ->paginate(15);

        $stats = [
            'total' => AdminInvite::count(),
            'pending' => AdminInvite::pending()->count(),
            'accepted' => AdminInvite::whereNotNull('accepted_at')->count(),
            'expired' => AdminInvite::expired()->count(),
        ];

        return view('admin.invites.index', compact('invites', 'stats'));
    }

    /**
     * Show invite creation form
     */
    public function create()
    {
        return view('admin.invites.create');
    }

    /**
     * Store a new invite and send email
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $email = strtolower($request->email);

        // Check if email already exists as a user
        if (User::where('email', $email)->exists()) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'This email is already registered as a user.']);
        }

        // Check if there's already a pending invite for this email
        $existingInvite = AdminInvite::where('email', $email)->first();

        if ($existingInvite) {
            if ($existingInvite->isValid()) {
                return back()
                    ->withInput()
                    ->withErrors(['email' => 'An invitation for this email is already pending.']);
            }

            // Delete expired or accepted invite to allow new one
            $existingInvite->delete();
        }

        // Create new invite
        $invite = AdminInvite::create([
            'email' => $email,
            'token' => Str::random(64),
            'invited_by' => \Illuminate\Support\Facades\Auth::id(),
            'expires_at' => now()->addHours(48),
        ]);

        // Send invite email
        Mail::to($email)->send(new AdminInviteMail($invite));

        return redirect()
            ->route('admin.invites.index')
            ->with('success', 'Invitation sent successfully to ' . $email);
    }

    /**
     * Revoke a pending invite
     */
    public function revoke(AdminInvite $invite)
    {
        if ($invite->isAccepted()) {
            return back()->with('error', 'Cannot revoke an already accepted invitation.');
        }

        $invite->delete();

        return back()->with('success', 'Invitation has been revoked.');
    }

    /**
     * Show the invite acceptance form (public route)
     */
    public function showAccept(string $token)
    {
        $invite = AdminInvite::where('token', $token)->first();

        if (!$invite) {
            return redirect()->route('login')
                ->with('error', 'Invalid invitation link.');
        }

        if ($invite->isAccepted()) {
            return redirect()->route('login')
                ->with('error', 'This invitation has already been used.');
        }

        if ($invite->isExpired()) {
            return redirect()->route('login')
                ->with('error', 'This invitation has expired. Please request a new one.');
        }

        return view('admin.invite-accept', compact('invite'));
    }

    /**
     * Process the invite acceptance and create admin user
     */
    public function accept(Request $request, string $token)
    {
        $invite = AdminInvite::where('token', $token)->first();

        if (!$invite || !$invite->isValid()) {
            return redirect()->route('login')
                ->with('error', 'Invalid or expired invitation.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'whatsapp_number' => ['required', 'string', 'max:20', 'unique:users,whatsapp_number'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // Create the admin user
        $user = User::create([
            'name' => $request->name,
            'email' => $invite->email,
            'whatsapp_number' => $request->whatsapp_number,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        // Auto-verify email since we sent the invite to this email
        // email_verified_at is not in fillable for security, so set it separately
        $user->email_verified_at = now();
        $user->save();

        // Mark invite as accepted
        $invite->update(['accepted_at' => now()]);

        return redirect()->route('login')
            ->with('success', 'Your admin account has been created successfully. Please login to continue.');
    }
}
