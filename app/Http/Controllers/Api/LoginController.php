<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Get the email and password from the request
        $email = $request->input('email');
        $password = $request->input('password');

        // Check if the user exists
        $user = DB::table('users')->where('email', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        // Verify the password
        if (Hash::check($password, $user->password)) {
            // Password is correct, create a token for the user
            $userModel = \App\Models\User::find($user->id); // Use Eloquent User model
            $token = $userModel->createToken('authToken')->plainTextToken;

            return response()->json([
                'message' => 'Login successful.',
                'token' => $token
            ], 200);
        }

        return response()->json(['message' => 'Invalid credentials.'], 401);
    }

    public function logout(Request $request)
    {
        // Check if the user is authenticated
        if ($request->user()) {
            // Revoke the user's current access token
            $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Logout successful.'], 200);
        }

        return response()->json(['message' => 'No authenticated user found.'], 401);
    }
}
