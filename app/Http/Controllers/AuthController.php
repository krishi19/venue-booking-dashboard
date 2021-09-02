<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|max:255',
        ]);
        $email = $request->email;
        $password = $request->password;
        if (auth()->guard('admin')->attempt(['email' => $email, 'password' => $password])) {

            $user = Auth::guard('admin')->user();
            $tokenResult = $user->createToken('Personal Access Token');
            return response()->json([
                    'token' =>$tokenResult->accessToken,
                    'role' =>$user->role,
            ]);
        } else {
            return response()->json([
                'message' => 'Invalid Email or Password.'
            ], 401);
        }
    }
}
