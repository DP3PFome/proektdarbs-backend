<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:6'
        ]);

        // Ja parole nav sniegta, ģenerēt jaunu
        $password = $validated['password'] ?? $this->generatePassword();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($password)
        ]);

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Lietotājs izveidots',
            'user' => $user,
            'token' => $token,
            'password' => $validated['password'] ? null : $password
        ]);
    }


    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $validated['email'])->first();

        if(!$user || !Hash::check($validated['password'],$user->password)){
            return response()->json([
                'message' => 'Nepareizs email vai parole'
            ],401);
        }

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Ienākšana veiksmīga',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255',
            'password' => 'nullable|string|min:6|confirmed'
        ]);

        $user = $request->user();
        $user->name = $validated['name'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return response()->json($user);
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:users,email,' . $request->user()->id,
            'password' => 'nullable|string|min:6'
        ]);

        $user = $request->user();
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

    private function generatePassword()
    {
        return Str::random(12);
    }

}
