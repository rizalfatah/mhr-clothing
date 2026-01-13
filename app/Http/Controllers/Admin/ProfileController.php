<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminPasswordChangeVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
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
