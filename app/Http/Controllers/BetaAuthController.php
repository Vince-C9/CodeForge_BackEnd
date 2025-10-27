<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;

/**
 * Beta Authentication Controller
 *
 * Handles authentication for beta access to the website.
 * Users can login but not register - accounts are created manually.
 */
class BetaAuthController extends Controller
{
    /**
     * Handle beta login request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find user by email (using username field)
        $user = User::where('email', $request->username)->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Generate a simple token (or you can use Laravel Sanctum)
        // For beta access, we'll just return success and set a cookie flag

        // Create the cookie
        $cookie = cookie(
            'beta_access',
            'granted',
            60 * 24 * 30, // 30 days
            '/',
            null, // domain (null = current domain)
            false, // secure (set to true in production with HTTPS)
            false, // httpOnly (set to false so JavaScript can read it)
            false, // raw
            'lax' // sameSite
        );

        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ]
        ])->cookie($cookie);
    }

    /**
     * Handle beta logout request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        return response()->json([
            'message' => 'Logout successful'
        ])->cookie(
            'beta_access',
            '', // empty value
            -1, // expired
            '/',
            null,
            false,
            true,
            false,
            'lax'
        );
    }

    /**
     * Check beta access status
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkAccess(Request $request)
    {
        $hasAccess = $request->cookie('beta_access') === 'granted';

        return response()->json([
            'has_access' => $hasAccess
        ]);
    }
}
