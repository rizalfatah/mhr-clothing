<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminPasswordChangeVerification;
use App\Mail\AdminEmailChangeVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    // ==========================================
    // PROFILE MANAGEMENT METHODS
    // ==========================================

    /**
     * Show admin profile edit page
     */
    public function index()
    {
        return view('admin.profile.edit', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Update admin profile (name, whatsapp - non-email fields)
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'whatsapp_number' => ['nullable', 'string', 'max:20', 'unique:users,whatsapp_number,' . $user->id],
        ], [
            'name.required' => 'Name is required.',
            'name.max' => 'Name must not exceed 255 characters.',
            'whatsapp_number.max' => 'WhatsApp number must not exceed 20 characters.',
            'whatsapp_number.unique' => 'This WhatsApp number is already registered.',
        ]);

        $user->update($validated);

        return redirect()->route('admin.profile.index')
            ->with('success', 'Profile updated successfully.');
    }

    // ==========================================
    // EMAIL CHANGE METHODS (WITH DUAL VERIFICATION)
    // ==========================================

    /**
     * Initiate email change - send verification code to old email
     */
    public function initiateEmailChange(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'new_email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        ], [
            'new_email.required' => 'New email address is required.',
            'new_email.email' => 'Please enter a valid email address.',
            'new_email.unique' => 'This email address is already registered.',
        ]);

        // Generate a 6-digit verification code for old email
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store email change data in session
        session([
            'email_change_new_email' => $validated['new_email'],
            'email_change_old_code' => $verificationCode,
            'email_change_old_code_expires_at' => now()->addMinutes(10),
            'email_change_old_verified' => false,
            'email_change_new_verified' => false,
        ]);

        // Send verification code to old email
        Mail::to($user->email)->send(new AdminEmailChangeVerification(
            $verificationCode,
            $user->name,
            'old_email',
            $validated['new_email']
        ));

        return redirect()->route('admin.profile.email.verify-old')
            ->with('success', 'Verification code has been sent to your current email.');
    }

    /**
     * Show old email verification form
     */
    public function showVerifyOldEmailForm()
    {
        // Check if email change was initiated
        if (!session('email_change_old_code')) {
            return redirect()->route('admin.profile.index')
                ->with('error', 'Please initiate email change first.');
        }

        // Check if code has expired
        if (now()->isAfter(session('email_change_old_code_expires_at'))) {
            $this->clearEmailChangeSession();
            return redirect()->route('admin.profile.index')
                ->with('error', 'Verification code has expired. Please try again.');
        }

        return view('admin.profile.verify-old-email', [
            'currentEmail' => auth()->user()->email,
            'newEmail' => session('email_change_new_email'),
        ]);
    }

    /**
     * Verify old email code
     */
    public function verifyOldEmail(Request $request)
    {
        $request->validate([
            'verification_code' => ['required', 'string', 'size:6'],
        ], [
            'verification_code.required' => 'Verification code is required.',
            'verification_code.size' => 'Verification code must be 6 digits.',
        ]);

        // Check if code has expired
        if (now()->isAfter(session('email_change_old_code_expires_at'))) {
            $this->clearEmailChangeSession();
            return redirect()->route('admin.profile.index')
                ->with('error', 'Verification code has expired. Please try again.');
        }

        // Verify the code
        if ($request->verification_code !== session('email_change_old_code')) {
            return back()->withErrors(['verification_code' => 'Invalid verification code.']);
        }

        // Mark old email as verified
        session(['email_change_old_verified' => true]);

        // Generate a new verification code for new email
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        session([
            'email_change_new_code' => $verificationCode,
            'email_change_new_code_expires_at' => now()->addMinutes(10),
        ]);

        // Send verification code to new email
        $user = auth()->user();
        Mail::to(session('email_change_new_email'))->send(new AdminEmailChangeVerification(
            $verificationCode,
            $user->name,
            'new_email'
        ));

        return redirect()->route('admin.profile.email.verify-new')
            ->with('success', 'Current email verified. Verification code has been sent to your new email.');
    }

    /**
     * Show new email verification form
     */
    public function showVerifyNewEmailForm()
    {
        // Check if old email was verified
        if (!session('email_change_old_verified')) {
            return redirect()->route('admin.profile.index')
                ->with('error', 'Please verify your current email first.');
        }

        // Check if code has expired
        if (now()->isAfter(session('email_change_new_code_expires_at'))) {
            $this->clearEmailChangeSession();
            return redirect()->route('admin.profile.index')
                ->with('error', 'Verification code has expired. Please try again.');
        }

        return view('admin.profile.verify-new-email', [
            'newEmail' => session('email_change_new_email'),
        ]);
    }

    /**
     * Verify new email code and complete email change
     */
    public function verifyNewEmail(Request $request)
    {
        $request->validate([
            'verification_code' => ['required', 'string', 'size:6'],
        ], [
            'verification_code.required' => 'Verification code is required.',
            'verification_code.size' => 'Verification code must be 6 digits.',
        ]);

        // Check if code has expired
        if (now()->isAfter(session('email_change_new_code_expires_at'))) {
            $this->clearEmailChangeSession();
            return redirect()->route('admin.profile.index')
                ->with('error', 'Verification code has expired. Please try again.');
        }

        // Verify the code
        if ($request->verification_code !== session('email_change_new_code')) {
            return back()->withErrors(['verification_code' => 'Invalid verification code.']);
        }

        // Update the user's email
        $user = auth()->user();
        $newEmail = session('email_change_new_email');
        $user->update([
            'email' => $newEmail,
            'email_verified_at' => now(), // Mark as verified since they confirmed via email
        ]);

        // Clear session data
        $this->clearEmailChangeSession();

        return redirect()->route('admin.profile.index')
            ->with('success', 'Email address changed successfully.');
    }

    /**
     * Resend verification code to old email
     */
    public function resendOldEmailCode()
    {
        $user = auth()->user();

        // Generate a new 6-digit verification code
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Update session
        session([
            'email_change_old_code' => $verificationCode,
            'email_change_old_code_expires_at' => now()->addMinutes(10),
        ]);

        // Send verification code to old email
        Mail::to($user->email)->send(new AdminEmailChangeVerification(
            $verificationCode,
            $user->name,
            'old_email',
            session('email_change_new_email')
        ));

        return back()->with('success', 'A new verification code has been sent to your current email.');
    }

    /**
     * Resend verification code to new email
     */
    public function resendNewEmailCode()
    {
        $user = auth()->user();

        // Generate a new 6-digit verification code
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Update session
        session([
            'email_change_new_code' => $verificationCode,
            'email_change_new_code_expires_at' => now()->addMinutes(10),
        ]);

        // Send verification code to new email
        Mail::to(session('email_change_new_email'))->send(new AdminEmailChangeVerification(
            $verificationCode,
            $user->name,
            'new_email'
        ));

        return back()->with('success', 'A new verification code has been sent to your new email.');
    }

    /**
     * Clear email change session data
     */
    private function clearEmailChangeSession()
    {
        session()->forget([
            'email_change_new_email',
            'email_change_old_code',
            'email_change_old_code_expires_at',
            'email_change_old_verified',
            'email_change_new_code',
            'email_change_new_code_expires_at',
            'email_change_new_verified',
        ]);
    }

    // ==========================================
    // PASSWORD CHANGE METHODS (EXISTING)
    // ==========================================

    /**
     * Show the password change form
     */
    public function edit()
    {
        return view('admin.profile.password');
    }

    /**
     * Send verification code to admin email
     */
    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
        ]);

        $user = auth()->user();

        // Generate a 6-digit verification code
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store the code in session with expiration time (10 minutes)
        session([
            'password_change_code' => $verificationCode,
            'password_change_code_expires_at' => now()->addMinutes(10),
            'password_change_verified' => false,
        ]);

        // Send the verification code via email
        Mail::to($user->email)->send(new AdminPasswordChangeVerification($verificationCode, $user->name));

        return redirect()->route('admin.profile.password.verify')
            ->with('success', 'Kode verifikasi telah dikirim ke email Anda.');
    }

    /**
     * Show the verification code form
     */
    public function showVerifyForm()
    {
        // Check if verification code was sent
        if (!session('password_change_code')) {
            return redirect()->route('admin.profile.password.edit')
                ->with('error', 'Silakan verifikasi password saat ini terlebih dahulu.');
        }

        // Check if code has expired
        if (now()->isAfter(session('password_change_code_expires_at'))) {
            session()->forget(['password_change_code', 'password_change_code_expires_at', 'password_change_verified']);
            return redirect()->route('admin.profile.password.edit')
                ->with('error', 'Kode verifikasi telah kedaluwarsa. Silakan coba lagi.');
        }

        return view('admin.profile.verify');
    }

    /**
     * Verify the code from email
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'verification_code' => ['required', 'string', 'size:6'],
        ], [
            'verification_code.required' => 'Kode verifikasi wajib diisi.',
            'verification_code.size' => 'Kode verifikasi harus 6 digit.',
        ]);

        // Check if code has expired
        if (now()->isAfter(session('password_change_code_expires_at'))) {
            session()->forget(['password_change_code', 'password_change_code_expires_at', 'password_change_verified']);
            return redirect()->route('admin.profile.password.edit')
                ->with('error', 'Kode verifikasi telah kedaluwarsa. Silakan coba lagi.');
        }

        // Verify the code
        if ($request->verification_code !== session('password_change_code')) {
            return back()->withErrors(['verification_code' => 'Kode verifikasi tidak valid.']);
        }

        // Mark as verified
        session(['password_change_verified' => true]);

        return redirect()->route('admin.profile.password.change')
            ->with('success', 'Verifikasi berhasil. Silakan masukkan password baru.');
    }

    /**
     * Show the new password form
     */
    public function showChangeForm()
    {
        // Check if user is verified
        if (!session('password_change_verified')) {
            return redirect()->route('admin.profile.password.edit')
                ->with('error', 'Silakan verifikasi email Anda terlebih dahulu.');
        }

        return view('admin.profile.change');
    }

    /**
     * Update the password
     */
    public function updatePassword(Request $request)
    {
        // Check if user is verified
        if (!session('password_change_verified')) {
            return redirect()->route('admin.profile.password.edit')
                ->with('error', 'Silakan verifikasi email Anda terlebih dahulu.');
        }

        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        $user = auth()->user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Clear session data
        session()->forget(['password_change_code', 'password_change_code_expires_at', 'password_change_verified']);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Password berhasil diubah.');
    }

    /**
     * Resend verification code
     */
    public function resendCode()
    {
        $user = auth()->user();

        // Generate a new 6-digit verification code
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Update the code in session with new expiration time (10 minutes)
        session([
            'password_change_code' => $verificationCode,
            'password_change_code_expires_at' => now()->addMinutes(10),
            'password_change_verified' => false,
        ]);

        // Send the verification code via email
        Mail::to($user->email)->send(new AdminPasswordChangeVerification($verificationCode, $user->name));

        return back()->with('success', 'Kode verifikasi baru telah dikirim ke email Anda.');
    }
}
