<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->input('email'))->firstOrFail();


        if (Hash::check($request->input('password'), $user->password)) {
            return UserResource::make($user);
        } else {
            return response()->json([
                'message' => 'These credentials do not match our records.'
            ], 404);
        }
    }
}
