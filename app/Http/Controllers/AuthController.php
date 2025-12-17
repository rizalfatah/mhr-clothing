<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\CartService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required',
        ]);

        $loginField = $request->input('login');
        $password = $request->input('password');

        // Determine if login field is email or phone number
        $fieldType = filter_var($loginField, FILTER_VALIDATE_EMAIL) ? 'email' : 'whatsapp_number';

        // Normalize phone number if it's not an email
        if ($fieldType === 'whatsapp_number') {
            $loginField = normalizePhoneNumber($loginField);
        }

        if (auth()->attempt([$fieldType => $loginField, 'password' => $password], $request->filled('remember'))) {
            $user = auth()->user();

            // Check if email is verified
            if (!$user->hasVerifiedEmail()) {
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('verification.notice')
                    ->withErrors(['email' => 'Please verify your email address before logging in.']);
            }

            $request->session()->regenerate();

            // Merge session cart to database for authenticated user
            $this->cartService->mergeSessionToDatabase();

            return redirect()->intended('/account');
        }

        return redirect()->back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('login', 'remember'));
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'whatsapp_number' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Normalize whatsapp_number to consistent format
        $validated['whatsapp_number'] = normalizePhoneNumber($validated['whatsapp_number']);

        $user = User::create($validated);

        // Send email verification notification
        event(new Registered($user));

        // Log the user in temporarily to access verification routes
        auth()->login($user);

        return redirect()->route('verification.notice')
            ->with('success', 'Registration successful! Please check your email to verify your account.');
    }

    public function resendVerification(Request $request)
    {
        // Rate limiting: 1 request per minute
        $key = 'resend-verification:' . $request->user()->id;

        if (RateLimiter::tooManyAttempts($key, 1)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Please wait {$seconds} seconds before requesting another verification email."
            ]);
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('account')
                ->with('info', 'Your email is already verified.');
        }

        $request->user()->sendEmailVerificationNotification();

        RateLimiter::hit($key, 60); // 60 seconds

        return back()->with('success', 'Verification email sent! Please check your inbox.');
    }
};
