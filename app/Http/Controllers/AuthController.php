<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        User::create($validated);

        return redirect()->route('login');
    }
};
